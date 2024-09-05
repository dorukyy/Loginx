<?php

namespace dorukyy\loginx\Models\Traits;

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
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Check if the user has any of the roles
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

}
