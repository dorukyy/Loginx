<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Login extends Model
{

  protected $guarded = [];
    protected $table = 'loginx_logins';
}
