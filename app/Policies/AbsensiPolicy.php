<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbsensiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_absensis::absensi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Absensi $absensi): bool
    {
        return $user->can('view_absensis::absensi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_absensis::absensi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Absensi $absensi): bool
    {
        return $user->can('update_absensis::absensi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Absensi $absensi): bool
    {
        return $user->can('delete_absensis::absensi');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_absensis::absensi');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Absensi $absensi): bool
    {
        return $user->can('force_delete_absensis::absensi');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_absensis::absensi');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Absensi $absensi): bool
    {
        return $user->can('restore_absensis::absensi');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_absensis::absensi');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Absensi $absensi): bool
    {
        return $user->can('replicate_absensis::absensi');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_absensis::absensi');
    }
}
