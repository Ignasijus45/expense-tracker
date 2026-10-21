<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ExpenseRepository implements ExpenseRepositoryInterface
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

    public function totalsByCategory(User $user): array
    {
        return $user->expenses()
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->selectRaw('categories.name as category, SUM(expenses.amount) as total')
            ->groupBy('categories.name')
            ->orderBy('categories.name')
            ->pluck('total', 'category')
            ->all();
    }
}
