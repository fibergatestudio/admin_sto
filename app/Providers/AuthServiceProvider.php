<?php

namespace App\Providers;

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

        /* Гейт: Администратор */
        Gate::define('admin_rights', function($user){
            return $user->isAdmin();
        });

        /* Гейт: Сотрудник */
        Gate::define('employee_rights', function($user){
            return $user->isEmployee();
        });

        /* Гейт : Снабженец */
        Gate::define('supply_officer_rights', function($user){
            return $user->isSupplyOfficer();
        });
    }
}
