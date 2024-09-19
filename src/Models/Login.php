<?php

namespace dorukyy\loginx\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * This is the model for user logins.
 */
class Login extends Model
{
    protected $guarded = [];
    protected $table = 'loginx_logins';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
