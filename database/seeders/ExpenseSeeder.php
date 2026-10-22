<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@test.com'],
            ['name' => 'Demo', 'password' => 'demo-password-123'],
        );

        Expense::factory()
            ->count(200)
            ->for($user)
            ->create();

        $rent = Category::where('name', 'Rent')->first();

        for ($i = 0; $i < 6; $i++) {
            Expense::factory()->for($user)->create([
                'category_id' => $rent->id,
                'amount' => 450,
                'description' => 'Rent',
                'spent_at' => now()->subMonths($i)->startOfMonth(),
            ]);
        }
    }
}
