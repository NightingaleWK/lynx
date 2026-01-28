<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Dish;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DishPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Dish');
    }

    public function view(AuthUser $authUser, Dish $dish): bool
    {
        return $authUser->can('View:Dish');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Dish');
    }

    public function update(AuthUser $authUser, Dish $dish): bool
    {
        return $authUser->can('Update:Dish');
    }

    public function delete(AuthUser $authUser, Dish $dish): bool
    {
        return $authUser->can('Delete:Dish');
    }

    public function restore(AuthUser $authUser, Dish $dish): bool
    {
        return $authUser->can('Restore:Dish');
    }

    public function forceDelete(AuthUser $authUser, Dish $dish): bool
    {
        return $authUser->can('ForceDelete:Dish');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Dish');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Dish');
    }

    public function replicate(AuthUser $authUser, Dish $dish): bool
    {
        return $authUser->can('Replicate:Dish');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Dish');
    }
}
