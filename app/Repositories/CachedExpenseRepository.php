<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CachedExpenseRepository implements ExpenseRepositoryInterface
{
    public function __construct(
        private ExpenseRepositoryInterface $inner,
    ) {}

    public function forUser(User $user): Collection
    {
        return $this->inner->forUser($user);
    }

    public function create(User $user, array $data): Expense
    {
        $expense = $this->inner->create($user, $data);

        Cache::forget($this->totalsKey($user));

        return $expense;
    }

    public function totalsByCategory(User $user): array
    {
        return Cache::remember(
            $this->totalsKey($user),
            now()->addHour(),
            fn () => $this->inner->totalsByCategory($user),
        );
    }

    private function totalsKey(User $user): string
    {
        return "expense-totals:{$user->id}";
    }

    public function search(User $user, string $term): Collection
    {
        return $this->inner->search($user, $term);
    }
}
