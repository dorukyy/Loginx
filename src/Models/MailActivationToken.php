<?php

namespace dorukyy\loginx\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class MailActivationToken extends Model
{
    protected $guarded = [];
    protected $table = 'loginx_mail_activation_tokens';
}
