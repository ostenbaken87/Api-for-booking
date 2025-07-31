<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use App\Services\ResourceService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use App\Repositories\Resource\ResourceRepository;
use App\Repositories\Resource\ResourceRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(
            ResourceRepositoryInterface::class,
            ResourceRepository::class
        );

        // Services
        $this->app->bind(ResourceService::class, function ($app) {
            return new ResourceService(
                $app->make(ResourceRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
