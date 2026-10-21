<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Collection;

class JsonExporter implements ExpenseExporter
{
    public function export(Collection $expenses): string
    {
        $rows = $expenses->map(fn ($expense) => [
            'date' => $expense->spent_at->format('Y-m-d'),
            'category' => $expense->category->name,
            'description' => $expense->description,
            'amount' => $expense->amount,
        ]);

        return json_encode($rows, JSON_PRETTY_PRINT);
    }

    public function contentType(): string
    {
        return 'application/json';
    }

    public function extension(): string
    {
        return 'json';
    }
}
