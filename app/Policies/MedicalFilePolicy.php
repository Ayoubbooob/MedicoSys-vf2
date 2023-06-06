<?php

namespace App\Policies;

use App\Models\medical_file;
use App\Models\User;
use App\Models\medicalFile;
use Illuminate\Auth\Access\Response;

class MedicalFilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['DOCTOR', 'MAJOR']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, medical_file $medicalFile): bool
    {
        return $user->hasRole(['DOCTOR', 'MAJOR']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('MAJOR');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, medical_file $medicalFile): bool
    {
        return $user->hasRole(['DOCTOR', 'MAJOR']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, medical_file $medicalFile): bool
    {
        return $user->hasRole('MAJOR');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, medical_file $medicalFile): bool
    {
        return $user->hasRole('MAJOR');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, medical_file $medicalFile): bool
    {
        return $user->hasRole('MAJOR');
    }
}
