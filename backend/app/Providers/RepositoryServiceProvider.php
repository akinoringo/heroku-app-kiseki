<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Effort\EffortRepositoryInterface;
use App\Repositories\Goal\GoalRepositoryInterface;
use App\Repositories\Effort\EffortRepository;
use App\Repositories\Goal\GoalRepository;


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
            GoalRepositoryInterface::class, GoalRepository::class);
        $this->app->bind(EffortRepositoryInterface::class, EffortRepository::class);
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
