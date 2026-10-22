<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::where('name', '!=', 'Rent')->inRandomOrder()->value('id'),
            'amount' => fake()->randomFloat(2, 2, 80),
            'description' => fake()->randomElement(['Groceries', 'Coffee', 'Bus ticket', 'Cinema', 'Lunch', 'Taxi', 'Books']),
            'spent_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
