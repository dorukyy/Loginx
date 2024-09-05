<?php

namespace dorukyy\loginx\Models\Traits;

use dorukyy\loginx\Models\MailActivationToken;
use dorukyy\loginx\Models\PasswordResetToken;
use dorukyy\loginx\Models\Timeout;
use dorukyy\loginx\SettingsFacade;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


trait Loginx
{

    public function isBlocked(): bool
    {
        if ($this->blocked_at) {
            if ($this->blocked_until) {
                return now()->isBefore($this->blocked_until);
            }
            return true;
        }
        return false;
    }

    /**
     * Check if the user is in timeout
     * @return bool
     */
    public function isTimeout(): bool
    {
        return $this->timeouts->where('unblocked_at', '>', now())->count() > 0;
    }

    /**
     * Get the end of the timeout
     * @return string|null
     */
    public function getEndOfTimeout(): ?string
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

    /**
     * Create a password reset token
     * @return string
     */
    public function createPasswordResetToken(): string
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

    /**
     * Get the user's timeouts
     * @return HasMany
     */
    public function timeouts(): HasMany
    {
        return $this->hasMany(Timeout::class);
    }

    /**
     * Check if today is the user's birthday
     * @return bool
     */
    public function isBirthdayToday(): bool
    {
        return now()->format('m-d') == $this->birthday->format('m-d');
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

}
