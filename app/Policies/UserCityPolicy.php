<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserCity;
use Illuminate\Auth\Access\Response;

class UserCityPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserCity $userCity): bool
    {
        return $user->id === $userCity->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserCity $userCity): bool
    {
        return $user->id === $userCity->user_id;
    }
}
