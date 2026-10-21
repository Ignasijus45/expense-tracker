<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Food', 'Rent', 'Transport', 'Fun', 'Other'];

        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
