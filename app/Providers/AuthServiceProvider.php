<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        // Define gates for all permissions dynamically
        Gate::before(function ($user, $ability) {
            // Super admin bypass (optional)
            if ($user->user_type->name == 'super_admin') {
                return true;
            }
            // Check if user has a role with the permission
            if ($user->roles()->exists()) {
                foreach ($user->roles as $role) {
                    $hasPermission = $role->permessions()
                        ->where('key', $ability)
                        ->exists();
                    
                    if ($hasPermission) {
                        return true;
                    }
                }
            }
            
            // Return false to deny, or null to continue to other gates
            return false;
        });
    }
}
