<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate:: define ('isAdmin', function($user){
            return $user->type === 'Admin';
        });

         Gate:: define ('isManager', function($user){
            return $user->type === 'Manager';
        });

         Gate:: define ('isMember', function($user){
            return $user->type === 'Member';

        });


        Passport::routes();


        //
    }
}
