<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
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
        $results = $user->expenses()
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->selectRaw("strftime('%Y-%m', expenses.spent_at) as month, categories.name as category, SUM(expenses.amount) as total")
            ->groupBy('month', 'categories.name')
            ->orderBy('month')
            ->orderBy('categories.name')
            ->get();

        $rows = [];

        foreach ($results as $result) {
            $rows[] = [
                'month' => $result->month,
                'category' => $result->category,
                'total' => round($result->total, 2),
            ];
        }

        return $rows;
    }

    public function weeklyTotals(User $user, string $month): array
    {
        $firstDay = Carbon::parse($month.'-01');
        $lastDay = $firstDay->copy()->endOfMonth();

        $expenses = $user->expenses()
            ->with('category')
            ->whereBetween('spent_at', [$firstDay, $lastDay])
            ->get();

        $rows = [];

        foreach ($expenses as $expense) {
            $week = intdiv($expense->spent_at->day - 1, 7) + 1;
            $category = $expense->category->name;
            $key = $category.'-'.$week;

            if (! isset($rows[$key])) {
                $rows[$key] = [
                    'week' => $week,
                    'category' => $category,
                    'total' => 0,
                ];
            }

            $rows[$key]['total'] = round($rows[$key]['total'] + $expense->amount, 2);
        }

        return array_values($rows);
    }
}
