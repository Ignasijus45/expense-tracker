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

    public function search(User $user, string $term): Collection
    {
        return $user->expenses()
            ->where('description', 'like', '%'.$term.'%')
            ->with('category')
            ->latest('spent_at')
            ->get();
    }

    public function monthlyTotals(User $user): array
    {
        return $user->expenses()
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->selectRaw("strftime('%Y-%m', expenses.spent_at) as month, categories.name as category, SUM(expenses.amount) as total")
            ->groupBy('month', 'categories.name')
            ->orderBy('month')
            ->orderBy('categories.name')
            ->get()
            ->map(fn ($row) => [
                'month' => $row->month,
                'category' => $row->category,
                'total' => round((float) $row->total, 2),
            ])
            ->all();
    }

    public function weeklyTotals(User $user, string $month): array
    {
        return $user->expenses()
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->whereRaw("strftime('%Y-%m', expenses.spent_at) = ?", [$month])
            ->selectRaw("((CAST(strftime('%d', expenses.spent_at) AS INTEGER) - 1) / 7) + 1 as week, categories.name as category, SUM(expenses.amount) as total")
            ->groupBy('week', 'categories.name')
            ->orderBy('week')
            ->orderBy('categories.name')
            ->get()
            ->map(fn ($row) => [
                'week' => (int) $row->week,
                'category' => $row->category,
                'total' => round((float) $row->total, 2),
            ])
            ->all();
    }
}
