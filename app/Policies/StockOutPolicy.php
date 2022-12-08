<?php

namespace App\Policies;

use App\Models\StockOut;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockOutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->can('view stockouts')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, StockOut $stockOut)
    {
        if ($user->can('view stockouts')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can('add stockout')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, StockOut $stockOut)
    {
        if ($user->can('edit stockout')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StockOut $stockOut)
    {
        if ($user->can('delete stockout')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, StockOut $stockOut)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StockOut  $stockOut
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, StockOut $stockOut)
    {
        //
    }
}
