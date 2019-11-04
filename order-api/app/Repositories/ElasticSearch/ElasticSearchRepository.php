<?php declare(strict_types=1);

namespace App\Repositories\ElasticSearch;

use Elasticsearch\ClientBuilder;

/**
 * Class ElasticSearchRepository
 * @package App\Repositories\ElasticSearch
 */
class ElasticSearchRepository implements ElasticSearchRepositoryInterface
{
    /**
     * @var \Elasticsearch\Client
     */
    private $clientBuilder;

    /**
     * ElasticSearchRepository constructor.
     *
     * @param ClientBuilder $clientBuilder
     */
    public function __construct(ClientBuilder $clientBuilder)
    {
        $url = config('services.elasticSearch.base_uri');

        $this->clientBuilder = $clientBuilder::create()
                                                ->setHosts([$url])
                                                ->build();
    }

    /**
     * @param array $params
     *
     * @return mixed|void
     */
    public function index(array $params)
    {
        $this->clientBuilder->index($params);
    }

    /**
     * @param string $index
     * @param        $id
     *
     * @return array|callable|mixed
     */
    public function get(string $index, $id)
    {
        try {
            $params = [
                'index' => $index,
                'id'    => $id
            ];

            return $this->clientBuilder->get($params);

        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function search(array $array): array
    {
        $result = $this->clientBuilder->search($array);

        if (empty($result['hits'])) {
            return [];
        }

        $hits = $result['hits'];

        if (empty($hits['hits'])) {
            return [];
        }

        $list = $hits['hits'];
        $data = [];
        foreach ($list as $item)
        {
            $data[] = $item['_source'];
        }

        return $data;
    }

    /**
     * @param string $index
     * @param        $id
     *
     * @return array|callable|mixed
     */
    public function getSource(string $index, $id)
    {
        try {
            $params = [
                'index' => $index,
                'id'    => $id
            ];

            $data = $this->clientBuilder->get($params);

            return $data["_source"];

        } catch (\Exception $exception) {

            return [];
        }
    }
}
