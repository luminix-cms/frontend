<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Workbench\App\Models\User;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('read-user', function (?User $currentUser, ?User $user) {
            if ($currentUser && $user) {
                return $currentUser->id === $user->id;
            }
            return false;
        });

        Gate::define('update-user', function (?User $currentUser, ?User $user) {
            if ($currentUser && $user) {
                return $currentUser->id === $user->id;
            }
            return false;
        });

        Gate::define('delete-user', function (?User $currentUser, ?User $user) {
            if ($currentUser && $user) {
                return $currentUser->id === $user->id;
            }
            return false;
        });

        Gate::define('create-user', function (?User $currentUser, ?User $user) {
            return true;
        });
    }
}
