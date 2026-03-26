<?php

namespace App\Policies;

use App\Models\ProviderWorkingHour;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderWorkingHourPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('provider');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProviderWorkingHour $providerWorkingHour): bool
    {
        return $user->hasRole('admin') || 
               $this->owns($user, $providerWorkingHour);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('provider');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProviderWorkingHour $providerWorkingHour): bool
    {
        return $user->hasRole('admin') || 
               $this->owns($user, $providerWorkingHour);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProviderWorkingHour $providerWorkingHour): bool
    {
        return $user->hasRole('admin') || 
               $this->owns($user, $providerWorkingHour);
    }

    /**
     * Determine if user owns the working hour (provider).
     */
    protected function owns(User $user, ProviderWorkingHour $workingHour): bool
    {
        $provider = $user->provider; // Assume User has provider relation or get via Provider::whereUserId
        return $provider && $provider->id === $workingHour->provider_id;
    }
}

