<?php

namespace App\Policies;

use App\Models\Effort;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EffortPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any efforts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the effort.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Effort  $effort
     * @return mixed
     */
    public function view(?User $user, Effort $effort)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create efforts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the effort.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Effort  $effort
     * @return mixed
     */
    public function update(User $user, Effort $effort)
    {
        //
        return $user->id === $effort->user_id;
    }

    /**
     * Determine whether the user can delete the effort.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Effort  $effort
     * @return mixed
     */
    public function delete(User $user, Effort $effort)
    {
        //
        return $user->id === $effort->user_id;
        
    }

    /**
     * Determine whether the user can restore the effort.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Effort  $effort
     * @return mixed
     */
    public function restore(User $user, Effort $effort)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the effort.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Effort  $effort
     * @return mixed
     */
    public function forceDelete(User $user, Effort $effort)
    {
        //
    }
}
