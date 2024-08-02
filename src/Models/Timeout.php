<?php

namespace dorukyy\loginx\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $ipAddress)
 * @method static create(array $array)
 */
class Timeout extends Model
{
    protected $guarded = [];
    protected $table = 'loginx_timeouts';

}
