<?php

namespace dorukyy\loginx\Models\Traits;

use dorukyy\loginx\Models\Permission;
use dorukyy\loginx\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait LoginxRoles
{
    public function addRolesFields(): void
    {
        $this->fillable = array_merge($this->fillable, [
            'role_id',
        ]);
    }

    public function addRolesCasts(): void
    {
        $this->casts = array_merge($this->casts, [
            'role_id' => 'integer',
        ]);
    }

    /**
     * Get the user's roles
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'loginx_users_roles', 'user_id', 'role_id');
    }

    /**
     * Check if the user has a role
     * @param  string  $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Check if the user has any of the roles
     * @return bool
     */
    public function hasAnyRole(): bool
    {
        return $this->roles->isNotEmpty();
    }

    /**
     * Return the user's roles
     * @param  array  $roles
     * @return bool
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'loginx_users_permissions', 'user_id', 'permission_id');
    }

    /**
     * Check if the user has a permission
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($base, $action): bool
    {
        return $this->permissions->contains(function ($permission) use ($base, $action) {
            return $permission->base === $base && $permission->action === $action;
        });
    }

    /**
     * Check if the user has any of the permissions
     * @return bool
     */
    public function hasAnyPermission(): bool
    {
        return $this->permissions->isNotEmpty();
    }

    public function hasPermissionTo($base, $action): bool
    {
        $permission = $this->hasPermission($base, $action);
        $inRoles = $this->roles->contains(function ($role) use ($base, $action) {
            return $role->permissions->contains(function ($permission) use ($base, $action) {
                return $permission->base === $base && $permission->action === $action;
            });
        });
        return $permission || $inRoles;
    }

}
