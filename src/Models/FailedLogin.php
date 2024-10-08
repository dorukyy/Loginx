<?php
namespace dorukyy\loginx\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * This is the model for user failed logins.
 */
class FailedLogin extends Model
{

  protected $guarded = [];
  protected $table = 'loginx_failed_logins';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
