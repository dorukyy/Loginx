<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $ipAddress)
 * @method static create(array $array)
 */
class BlockedMailProvider extends Model
{
    public $timestamps = false;
    protected $fillable = ['url'];

    protected $guarded = [];
    protected $table = 'loginx_blocked_mail_providers';
}
