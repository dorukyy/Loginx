<?php

namespace dorukyy\loginx\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $table = 'loginx_notifications';
    protected $fillable = ['user_id',];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
