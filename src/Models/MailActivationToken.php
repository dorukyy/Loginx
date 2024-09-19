<?php

namespace dorukyy\loginx\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class MailActivationToken extends Model
{
    protected $guarded = [];
    protected $table = 'loginx_mail_activation_tokens';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
