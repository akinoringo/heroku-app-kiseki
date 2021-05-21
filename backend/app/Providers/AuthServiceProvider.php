<?php

namespace App\Providers;

use App\Policies\EffortPolicy;
use App\Policies\GoalPolicy;
use App\Models\Effort;
use App\Models\Goal;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Effort::class => EffortPolicy::class,
        Goal::class => GoalPolicy::class        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
