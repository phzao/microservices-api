<?php declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\ElasticSearch\ElasticSearchRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;

/**
 * Class BaseRepository
 * @package App\Repository
 */
class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var
     */
    protected $elastic;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->elastic = App::make(ElasticSearchRepositoryInterface::class);
    }

    /**
     * @param array $content
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(array $content)
    {
        try {
            $data = $this->model::create($content);

            if(env('APP_ENV')!=='testing') {
                $this->elastic->index($data->getFullDataToIndex());
            }

            return $data;
        } catch (\Exception $e) {
            $errormsg["message"] = $e->getMessage();

            throw new \Exception(json_encode($errormsg));
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]|mixed
     */
    public function all()
    {
        return $this->model::all();
    }

    /**
     * @param array $content
     *
     * @return array|\Illuminate\Database\Eloquent\Collection|Model[]|mixed
     */
    public function allBy(array $content)
    {
        if (empty($content)) {
            return $this->model::all();
        }

        $params = $this->model->getSearchParams($content);
        try {
            return $this->elastic->search($params);
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param string $table
     */
    public function clearRedisCache(string $table)
    {
        Redis::flushDb($table);
    }

    /**
     * @param $id
     *
     * @return int|mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        $status = $this->model::destroy($id);

        if (!$status) {
            throw new \Exception("Record does not exist!");
        }

        return $status;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model::find($id);
    }

    /**
     * @param       $id
     * @param array $content
     *
     * @return mixed
     */
    public function update($id, array $content)
    {
        return $this->model::find($id)->update($content);
    }
}
