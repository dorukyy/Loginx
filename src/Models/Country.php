<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $ipAddress)
 * @method static create(array $array)
 */
class Country extends Model
{

    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'loginx_countries';
}
