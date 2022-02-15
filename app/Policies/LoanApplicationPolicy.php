<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class LoanApplicationPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LoanApplication $loanApplication)
    {
        return $user->is_admin || $user->id == $loanApplication->user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LoanApplication $loanApplication)
    {
        return !empty(request()->post('status')) ? $user->is_admin : $user->id == $loanApplication->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LoanApplication $loanApplication)
    {
        return $user->is_admin || $user->id == $loanApplication->user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, LoanApplication $loanApplication)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, LoanApplication $loanApplication)
    {
        //
    }
}
