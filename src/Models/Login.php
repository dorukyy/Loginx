<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Login extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'loginx_logins';


}
