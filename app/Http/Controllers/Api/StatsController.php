<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ExpenseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(
        private ExpenseRepositoryInterface $expenses,
    ) {}

    public function monthly(Request $request): JsonResponse
    {
        $rows = $this->expenses->monthlyTotals($request->user());

        $months = [];
        foreach ($rows as $row) {
            if (! in_array($row['month'], $months)) {
                $months[] = $row['month'];
            }
        }

        return response()->json($this->buildChart($rows, 'month', $months));
    }

    public function weekly(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $month = $validated['month'];

        $rows = $this->expenses->weeklyTotals($request->user(), $month);

        $daysInMonth = Carbon::parse($month.'-01')->daysInMonth;
        $weekCount = (int) ceil($daysInMonth / 7);
        $weeks = range(1, $weekCount);

        return response()->json($this->buildChart($rows, 'week', $weeks));
    }

    private function buildChart(array $rows, string $periodKey, array $labels): array
    {
        $categories = [];
        foreach ($rows as $row) {
            if (! in_array($row['category'], $categories)) {
                $categories[] = $row['category'];
            }
        }
        sort($categories);

        $datasets = [];
        foreach ($categories as $category) {
            $data = [];
            foreach ($labels as $label) {
                $data[] = $this->findTotal($rows, $periodKey, $label, $category);
            }

            $datasets[] = [
                'label' => $category,
                'data' => $data,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    private function findTotal(array $rows, string $periodKey, $label, string $category): float
    {
        foreach ($rows as $row) {
            if ($row[$periodKey] === $label && $row['category'] === $category) {
                return $row['total'];
            }
        }

        return 0;
    }
}
