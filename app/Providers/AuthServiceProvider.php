<?php

namespace App\Providers;

use App\Offer;
use App\Proposal;
use App\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('freelancer', function ($user) {
            return $user->current_account === 'freelancer';
        });

        Gate::define('client', function ($user) {
            return $user->current_account === 'client';
        });

        Gate::define('not-owner', function ($user, $id) {
            return $user->id !== $id;
        });

        Gate::define('owner', function ($user, $id) {
            return $user->id === $id;
        });

        Gate::define('not-accepted', function ($user, Model $model) {
            return $model->accepted == false;
        });

        Passport::routes();
    }
}
