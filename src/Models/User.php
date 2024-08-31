<?php

namespace App\Models;

use dorukyy\loginx\Models\MailActivationToken;
use dorukyy\loginx\Models\PasswordResetToken;
use dorukyy\loginx\Models\Permission;
use dorukyy\loginx\Models\Role;
use dorukyy\loginx\Models\Timeout;
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
    use Notifiable, SoftDeletes, HasFactory;


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

    public function sendEmailVerificationMail(): void
    {
        //get the token
        $mailToken = MailActivationToken::where('user_id', $this->id)->first();
        //if the token exists and not expired
        if (!$mailToken || $mailToken->created_at->addSeconds(180) < now()) {
            //create a new token
            $token = Str::random(60);
            while (MailActivationToken::where('token', $token)->exists()) {
                $token = Str::random(60);
            }
            $mailToken = MailActivationToken::create([
                'user_id' => $this->id,
                'expires_at' => now()->addSeconds(SettingsFacade::getActivationTokenDuration()),
                'token' => $token
            ]);
        }
        $url = route('activation.activate', ['token' => $mailToken->token]);
        Mail::send('loginx::activation.activate-mail', ['url' => $url], function ($message) {
            $message->to($this->email);
            $message->subject('Mail Activation');
        });

    }

    /**
     * Get the user's permissions
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'loginx_users_permissions', 'user_id', 'role_id');
    }

    /**
     * Get the user's timeouts
     * @return HasMany
     */
    public function timeouts(): HasMany
    {
        return $this->hasMany(Timeout::class);
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
     * Check if today is the user's birthday
     * @return bool
     */
    public function isBirthdayToday(): bool
    {
        return now()->format('m-d') == $this->birthday->format('m-d');
    }

    /**
     * Get the user's referral count
     * @return int
     */
    public function referralCount(): int
    {
        return User::where('referred_by', $this->id)->count();
    }

    /**
     * Check if the user is blocked
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked_at != null && $this->blocked_until != null && $this->blocked_until > now();
    }

    /**
     * Check if the user is in timeout
     * @return bool
     */
    public function isTimeout(): bool
    {
        return $this->timeouts->where('unblocked_at', '>', now())->count() > 0;
    }

    public function getEndOfTimeout()
    {
        return $this->timeouts->where('unblocked_at', '>', now())->first()->unblocked_at ?? null;
    }

    /**
     * Check if the user is activated
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->email_verified_at != null;
    }

    public function createPasswordResetToken()
    {
        $token = substr(bcrypt(Str::random(60)), 7);
        while (PasswordResetToken::where('token', $token)->exists()) {
            $token = substr(bcrypt(Str::random(60)), 7);
        }
        PasswordResetToken::create([
            'email' => $this->email,
            'user_id' => $this->id,
            'token' => $token
        ]);

        return $token;

    }

}
