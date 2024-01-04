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

        /**
         * User Roles  
         *  - 0   CEO: all access
         *  - 1   Sales: only create and view transactions
         *  - 2   Compliance team: only view both Transactions and Customers
         *  - 3   Accounting: access to accounting
         *  @see  App\Models\User 
         */
        Gate::define('create-transactions', function($user){
            $role = $user->role;
            return $role == 0 || $role == 1 ;
        });
        Gate::define('view-transactions', function($user){
            $role = $user->role;
            return $role == 0 || $role == 1 || $role == 2;
        });
        Gate::define('view-customers', function($user){
            $role = $user->role;
            return $role == 0 || $role == 2;
        });
        Gate::define('create-expenses', function($user){
            $role = $user->role;
            return $role == 0 || $role == 3;
        });
        Gate::define('view-expenses', function($user){
            $role = $user->role;
            return $role == 0 || $role == 3;
        });

        // for only the admins
        Gate::define('create-currencies', function($user){return $user->role == 0;});
        Gate::define('create-payment-methods', function($user){return $user->role == 0;});
        Gate::define('create-users', function($user){return $user->role == 0;});
        Gate::define('export-import-data', function($user){return $user->role == 0;});
        Gate::define('change-settings', function($user){return $user->role == 0;});
        Gate::define('create-employees', function($user){return $user->role == 0;});
        Gate::define('view-employees', function($user){return $user->role == 0;});
    }
}
