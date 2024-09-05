<?php

namespace dorukyy\loginx\Models\Traits;

trait LoginxReferral
{
    public static function booted(): void
    {
        static::creating(function ($model) {
            $referralCode = substr(md5(uniqid(rand(), true)), 0, 6);
            while (self::where('referral_code', $referralCode)->exists()) {
                $referralCode = substr(md5(uniqid(rand(), true)), 0, 6);
            }
            $model->referral_code = $referralCode;

        });
    }

    public function referredBy()
    {
        return $this->belongsTo(self::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(self::class, 'referrer_id');
    }

    public function getReferralLink(): string
    {
        return route('register', ['referral_code' => $this->referral_code]);
    }

    public function getReferralCount(): int
    {
        return $this->referrals()->count();
    }





}
