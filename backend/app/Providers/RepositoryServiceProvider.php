<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Goal\GoalGetRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            GoalRepositoryInterface::class, GoalGetRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
