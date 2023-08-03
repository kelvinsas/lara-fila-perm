<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy
{
       /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read_batch');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Batch $batch): bool
    {
        return $user->hasPermissionTo('read_batch');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_batch');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Batch $batch): bool
    {
        return $user->hasPermissionTo('update_batch');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Batch $batch): bool
    {
        return $user->hasPermissionTo('delete_batch');
    }
}
