<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ExpenseRepository
{
    public function forUser(User $user): Collection
    {
        return $user->expenses()
            ->with('category')
            ->latest('spent_at')
            ->get();
    }

    public function create(User $user, array $data): Expense
    {
        return $user->expenses()->create($data);
    }
}