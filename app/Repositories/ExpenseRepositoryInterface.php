<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ExpenseRepositoryInterface
{
    public function forUser(User $user): Collection;

    public function create(User $user, array $data): Expense;
}
