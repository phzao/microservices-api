<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * Interface OrderRepositoryInterface
 * @package App\Repositories
 */
interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param $id
     *
     * @return null|array
     */
    public function allByUser($id);
}
