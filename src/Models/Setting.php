<?php

namespace dorukyy\loginx\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1)
 */
class Setting extends Model
{
    protected $table = 'loginx_settings';
    public $incrementing = false;
    protected $primaryKey = 'key';
    public $timestamps = false;

}
