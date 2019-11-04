<?php declare(strict_types=1);

namespace App\Providers;

use App\Repositories\ElasticSearch\ElasticSearchRepository;
use App\Repositories\ElasticSearch\ElasticSearchRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class ElasticSearchServiceProvider
 * @package App\Providers
 */
class ElasticSearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ElasticSearchRepositoryInterface::class,
            ElasticSearchRepository::class

        );
    }
}

