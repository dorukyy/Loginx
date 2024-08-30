<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Permission extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'loginx_permissions';
}
