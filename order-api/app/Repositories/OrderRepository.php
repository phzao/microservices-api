<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Redis;

/**
 * Class OrderRepository
 * @package App\Repositories
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = new Order();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|mixed
     */
    public function all()
    {
        if ($orders = Redis::get('orders.all')) {
            return json_decode($orders, true);
        }

        $orders = parent::all();

        Redis::set('orders.all', $orders);

        return $orders;
    }

    /**
     * @param $id
     *
     * @return null|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|mixed
     */
    public function allByUser($id)
    {
//        if ($orders = Redis::get('orders_by_user.all')) {
//            return json_decode($orders, true);
//        }
//
        $orders = $this->model::where(["user_id" => $id])
                                ->get();
//
//        Redis::set('orders_by_user.all', $orders);

        return $orders;
    }
}
