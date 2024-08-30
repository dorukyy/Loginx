<?php

namespace dorukyy\loginx\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 */
class RegisterRequest extends Model
{
    protected $guarded = [];
    protected $table = 'loginx_register_requests';


}
