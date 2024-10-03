<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Role extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'loginx_roles';

    public function users()
    {
        return $this->belongsToMany(config('loginx.user_model'), 'loginx_users_roles', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'loginx_roles_permissions', 'role_id', 'permission_id');
    }


}
