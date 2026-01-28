<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\IngredientAisle;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class IngredientAislePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:IngredientAisle');
    }

    public function view(AuthUser $authUser, IngredientAisle $ingredientAisle): bool
    {
        return $authUser->can('View:IngredientAisle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:IngredientAisle');
    }

    public function update(AuthUser $authUser, IngredientAisle $ingredientAisle): bool
    {
        return $authUser->can('Update:IngredientAisle');
    }

    public function delete(AuthUser $authUser, IngredientAisle $ingredientAisle): bool
    {
        return $authUser->can('Delete:IngredientAisle');
    }

    public function restore(AuthUser $authUser, IngredientAisle $ingredientAisle): bool
    {
        return $authUser->can('Restore:IngredientAisle');
    }

    public function forceDelete(AuthUser $authUser, IngredientAisle $ingredientAisle): bool
    {
        return $authUser->can('ForceDelete:IngredientAisle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:IngredientAisle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:IngredientAisle');
    }

    public function replicate(AuthUser $authUser, IngredientAisle $ingredientAisle): bool
    {
        return $authUser->can('Replicate:IngredientAisle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:IngredientAisle');
    }
}
