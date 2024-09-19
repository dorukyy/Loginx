<?php

namespace dorukyy\loginx\Models\Traits;

trait UserDetails
{
    public function addDetailsFields(): void
    {
        $this->fillable = array_merge($this->fillable, [
            'name',
            'surname',
            'username',
            'birthday',
            'phone',
            'avatar',
            'referrer_id',
            'preferred_language',
            'timezone',
            'preferred_date_format',
            'referral_code',
            'country_id',
            'address',
        ]);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name.' '.$this->surname;
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : asset('images/default-avatar.png');
    }



}
