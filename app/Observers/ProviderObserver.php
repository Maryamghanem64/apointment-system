<?php

namespace App\Observers;

use App\Models\Provider;
use Illuminate\Support\Facades\Cache;

class ProviderObserver
{
    public function saved(Provider $provider): void
    {
        // Invalidate all availability caches for this provider
        $keys = Cache::getRedis()->keys("provider:*:{$provider->id}*");
        if ($keys) {
            Cache::getRedis()->del($keys);
        }
    }

    public function deleted(Provider $provider): void
    {
        $this->saved($provider);
    }

    public function forceDeleted(Provider $provider): void
    {
        $this->saved($provider);
    }
}
