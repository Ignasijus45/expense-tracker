<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Collection;

class CsvExporter implements ExpenseExporter
{
    public function export(Collection $expenses): string
    {
        $csv = "date,category,description,amount\n";

        foreach ($expenses as $expense) {
            $date = $expense->spent_at->format('Y-m-d');
            $category = $expense->category->name;
            $description = str_replace(',', ' ', $expense->description ?? '');
            $amount = $expense->amount;

            $csv .= "$date,$category,$description,$amount\n";
        }

        return $csv;
    }

    public function contentType(): string
    {
        return 'text/csv';
    }

    public function extension(): string
    {
        return 'csv';
    }
}
