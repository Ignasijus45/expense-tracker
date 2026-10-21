<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Collection;

class CsvExporter implements ExpenseExporter
{
    public function export(Collection $expenses): string
    {
        $file = fopen('php://temp', 'r+');

        fputcsv($file, ['date', 'category', 'description', 'amount'], escape: '');

        foreach ($expenses as $expense) {
            fputcsv($file, [
                $expense->spent_at->format('Y-m-d'),
                $expense->category->name,
                $expense->description,
                $expense->amount,
            ], escape: '');
        }

        rewind($file);
        $csv = stream_get_contents($file);
        fclose($file);

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
