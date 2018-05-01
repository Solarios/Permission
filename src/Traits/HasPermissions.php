<?php

namespace Solarios\Permission\Traits;

use Solarios\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasPermissions
{
    /**
     * A model may have multiple permissions.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions(): MorphToMany
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }

    /**
     * Check if the model has the permission.
     *
     * @param string  $permission
     * @return boolean
     */
    public function hasPermissionTo($permission): bool
    {
        if (isset(class_uses(self::class)[HasRoles::class])) {
            $exists = $this->whereHas('roles.permissions', function ($query) use ($permission) {
                $query->where('title', $permission);
            })->exists();
        }

        return ($exists ?? false) || $this->permissions()->where('title', $permission)->exists();
    }

    /**
     * Revoke the permission from the model.
     *
     * @param mixed  $permission
     * @return self
     */
    public function revokePermissionTo($permission): self
    {
        if (is_string($permission)) {
            $permission = Permission::where('title', $permission)->first();
        }
        
        if (is_int($permission) || is_array($permission)) {
            $permission = Permission::find($permission);
        }

        return tap($this, function ($model) use ($permission) {
            $model->permissions()->detach($permission);
        });
    }

    /**
     * Give the permission to the model.
     *
     * @param mixed  $permission
     * @return self
     */
    public function givePermissionTo($permission): self
    {
        if (is_string($permission)) {
            $permission = Permission::firstOrCreate(['title' => $permission]);
        }
        
        if (is_int($permission) || is_array($permission)) {
            $permission = Permission::find($permission);
        }

        return tap($this, function ($model) use ($permission) {
            $model->permissions()->attach($permission);
        });
    }
}