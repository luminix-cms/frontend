<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Workbench\App\Models\User;
use Workbench\App\Models\Post;

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

        /* * */

        Gate::define('read-post', function (?User $currentUser, ?Post $toDo) {
            if ($currentUser && $toDo) {
                return $currentUser->id === $toDo->user_id;
            }
            return !is_null($currentUser); 
            // if not logged in, return false
            // results will be filtered by scopeAllowed
        });

        Gate::define('update-post', function (?User $currentUser, ?Post $toDo) {
            if ($currentUser && $toDo) {
                return $currentUser->id === $toDo->user_id;
            }
            return false;
        });

        Gate::define('delete-post', function (?User $currentUser, ?Post $toDo) {
            if ($currentUser && $toDo) {
                return $currentUser->id === $toDo->user_id;
            }
            return false;
        });

        Gate::define('create-post', function (?User $currentUser, ?Post $toDo) {
            return !is_null($currentUser);
        });
    }
}
