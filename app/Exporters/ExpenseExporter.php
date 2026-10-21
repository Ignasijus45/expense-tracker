<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Collection;

interface ExpenseExporter
{
    public function export(Collection $expenses): string;

    public function contentType(): string;

    public function extension(): string;
}
