<?php

namespace App\Support;

class ChartData
{
    public static function fromRows(array $rows, string $periodKey, array $labels): array
    {
        $totals = [];

        foreach ($rows as $row) {
            $totals[$row['category']][$row[$periodKey]] = $row['total'];
        }

        ksort($totals);

        $datasets = [];

        foreach ($totals as $category => $byPeriod) {
            $datasets[] = [
                'label' => $category,
                'data' => array_map(fn ($label) => $byPeriod[$label] ?? 0, $labels),
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
}
