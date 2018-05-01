<?php

namespace Solarios\Permission;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGate();
    }

    /**
     * Register a new before gate for authorization.
     *
     * @return void
     */
    protected function registerGate()
    {
        $this->app[Gate::class]->before(function ($user, string $ability) {
            if (method_exists($user, 'hasPermissionTo')) {
                return $user->hasPermissionTo($ability) ?: null;
            }
        });
    }
}