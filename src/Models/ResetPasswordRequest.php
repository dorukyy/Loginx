<?php

namespace dorukyy\loginx\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 */
class ResetPasswordRequest extends Model
{
    protected $guarded = [];
    protected $table = 'loginx_reset_password_requests';


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



}
