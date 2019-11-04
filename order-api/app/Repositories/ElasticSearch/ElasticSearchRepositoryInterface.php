<?php declare(strict_types=1);

namespace App\Repositories\ElasticSearch;

/**
 * Interface ElasticSearchRepositoryInterface
 * @package App\Repositories\ElasticSearch
 */
interface ElasticSearchRepositoryInterface
{
    /**
     * @param array $params
     *
     * @return mixed
     */
    public function index(array $params);

    /**
     * @param string $index
     * @param        $id
     *
     * @return mixed
     */
    public function get(string $index, $id);

    /**
     * @param string $index
     * @param        $id
     *
     * @return mixed
     */
    public function getSource(string $index, $id);

    /**
     * @param array $array
     *
     * @return array
     */
    public function search(array $array): array;
}
