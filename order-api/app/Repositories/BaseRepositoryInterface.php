<?php declare(strict_types=1);

namespace App\Repositories;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repository
 */
interface BaseRepositoryInterface
{
    /**
     * @param array $content
     *
     * @return mixed
     */
    public function create(array $content);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getById($id);

    /**
     * @param       $id
     * @param array $content
     *
     * @return mixed
     */
    public function update($id, array $content);

    /**
     * @param string $table
     *
     * @return mixed
     */
    public function clearRedisCache(string $table);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param array $filter
     *
     * @return mixed
     */
    public function allBy(array $filter);
}
