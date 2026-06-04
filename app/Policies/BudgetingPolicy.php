<?php

namespace App\Policies;

use App\Models\Budgeting;
use App\Models\User;

class BudgetingPolicy
{
    public function view(User $user, Budgeting $budgeting): bool
    {
        return $user->id === $budgeting->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Budgeting $budgeting): bool
    {
        return $user->id === $budgeting->user_id;
    }

    public function delete(User $user, Budgeting $budgeting): bool
    {
        return $user->id === $budgeting->user_id;
    }
}
