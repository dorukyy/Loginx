<?php

namespace dorukyy\loginx\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * This is the model for user API keys.
 */
class ApiKey extends Model
{
    use SoftDeletes;

    protected $table = 'loginx_api_keys';
    protected $fillable = ['user_id', 'api_key', 'is_active'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
