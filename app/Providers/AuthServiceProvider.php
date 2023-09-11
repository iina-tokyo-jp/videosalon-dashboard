<?php

namespace App\Providers;

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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // FNC1
        Gate::define('fnc1', function ($user) {
            return ($user->authority >= 1 && $user->authority <= 2);
        });

        // FNC2
        Gate::define('fnc2', function ($user) {
            return ($user->authority >= 1 && $user->authority <= 2);
        });

        // FNC3
        Gate::define('fnc3', function ($user) {
            return ($user->authority >= 1 && $user->authority <= 3);
        });

        // FNC4
        Gate::define('fnc4', function ($user) {
            return ($user->authority >= 1 && $user->authority <= 3);
        });

        // FNC5
        Gate::define('fnc5', function ($user) {
            return ($user->authority >= 1 && $user->authority <= 3);
        });

        // FNC6
        Gate::define('fnc6', function ($user) {
            return (($user->authority >= 1 && $user->authority <= 4) && $user->authority != 3);
        });

        // FNC7
        Gate::define('fnc7', function ($user) {
            return (($user->authority >= 1 && $user->authority <= 4) && $user->authority != 2);
        });

        // FNC8
        Gate::define('fnc8', function ($user) {
            return (($user->authority >= 1 && $user->authority <= 4) && $user->authority != 2);
        });

        // FNC9
        Gate::define('fnc9', function ($user) {
            return (($user->authority >= 1 && $user->authority <= 4) && $user->authority != 2);
        });

        // FNC10
        Gate::define('fnc10', function ($user) {
            return (($user->authority >= 1 && $user->authority <= 4) && $user->authority != 2);
        });
    }
}
