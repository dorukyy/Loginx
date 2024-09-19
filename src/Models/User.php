<?php

namespace App\Models;

use dorukyy\loginx\Models\MailActivationToken;
use dorukyy\loginx\Models\PasswordResetToken;
use dorukyy\loginx\Models\Permission;
use dorukyy\loginx\Models\Role;
use dorukyy\loginx\Models\Timeout;
use dorukyy\loginx\Models\Traits\UserDetails;
use dorukyy\loginx\SettingsFacade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * @method static where(string $string, $user_input)
 * @method static find($userID)
 * @method static create(array $array)
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasFactory,UserDetails;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'username',
        'birthday',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $referral_code = Str::random(12);
                while (User::where('referral_code', $user->referral_code)->exists()) {
                    $referral_code = Str::random(12);
                }
                $user->referral_code = $referral_code;
            }
        });

        static::created(function ($user) {
            if (!SettingsFacade::getIsEmailActivation()) {
                $user->email_verified_at = now();
            } else {
                $token = Str::random(60);
                while (MailActivationToken::where('token', $token)->exists()) {
                    $token = Str::random(60);
                }
                MailActivationToken::create([
                    'user_id' => $user->id,
                    'token' => $token
                ]);
            }
            $user->save();
        });
    }

    /**
     * Get the user's roles
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'loginx_users_roles', 'user_id', 'role_id');
    }



    /**
     * Get the user's permissions
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'loginx_users_permissions', 'user_id', 'role_id');
    }


    public function hasPermission($permission): bool
    {
        $roles = $this->roles;
        $permissions = $this->permissions;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get the user's referral count
     * @return int
     */
    public function referralCount(): int
    {
        return User::where('referred_by', $this->id)->count();
    }


}
