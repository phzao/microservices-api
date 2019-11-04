<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Redis;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = new User();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|mixed
     */
    public function all()
    {
        if ($users = Redis::get('users.all')) {
            return json_decode($users, true);
        }

        $users = parent::all();

        Redis::set('users.all', $users);

        return $users;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|mixed
     */
    public function allTest()
    {
        return $this->all();
    }
}
