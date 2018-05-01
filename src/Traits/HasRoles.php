<?php

namespace Solarios\Permission\Traits;

use Solarios\Permission\Models\Role;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRoles
{
    /**
     * A model may have multiple roles.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'rolable');
    }

    /**
     * Check if the user has the role.
     *
     * @param string $permission
     * @return boolean
     */
    public function hasRole($role): bool
    {
        return $this->roles()->where('title', $role)->exists();
    }

    /**
     * Revoke the role from the model.
     *
     * @param mixed  $role
     * @return self
     */
    public function revokeRole($role): self
    {
        if (is_string($role)) {
            $role = Role::where('title', $role)->first();
        }
        
        if (is_int($role) || is_array($role)) {
            $role = Role::find($role);
        }

        return tap($this, function ($model) use ($role) {
            $model->roles()->detach($role);
        });
    }

    /**
     * Give the role to the model.
     *
     * @param mixed  $role
     * @return self
     */
    public function giveRole($role): self
    {
        if (is_string($role)) {
            $role = Role::firstOrCreate(['title' => $role]);
        }
        
        if (is_int($role) || is_array($role)) {
            $role = Role::find($role);
        }

        return tap($this, function ($model) use ($role) {
            $model->roles()->attach($role);
        });
    }
}