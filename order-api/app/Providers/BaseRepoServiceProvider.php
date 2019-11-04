<?php declare(strict_types=1);

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class BaseRepoServiceProvider
 * @package App\Repository
 */
class BaseRepoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            BaseRepositoryInterface::class,
            BaseRepository::class

        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
    }
}
