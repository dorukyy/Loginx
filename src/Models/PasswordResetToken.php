<?php

namespace dorukyy\loginx\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PasswordResetToken
 *
 */
class PasswordResetToken extends Model
{

    protected $guarded = [];
    protected $table = 'loginx_password_reset_tokens';
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
