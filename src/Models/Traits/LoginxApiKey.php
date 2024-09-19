<?php

namespace dorukyy\loginx\Models\Traits;

use dorukyy\loginx\Models\ApiKey;

trait LoginxApiKey
{
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class, 'user_id', 'id')->get();
    }

    public function createApiKey()
    {
        return $this->apiKeys()->create([
            'api_key' => md5(uniqid(rand(), true)),
            'is_active' => 1
        ]);
    }

}
