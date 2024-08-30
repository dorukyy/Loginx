<?php

namespace dorukyy\loginx\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class PasswordResetToken extends Model
{

    protected $guarded = [];
    protected $table = 'password_reset_tokens';
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
