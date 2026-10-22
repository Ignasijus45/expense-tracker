<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ExpenseRepositoryInterface;
use App\Support\ChartData;
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

        $months = array_values(array_unique(array_column($rows, 'month')));

        return response()->json(ChartData::fromRows($rows, 'month', $months));
    }

    public function weekly(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $month = $validated['month'];

        $rows = $this->expenses->weeklyTotals($request->user(), $month);

        $daysInMonth = Carbon::parse($month.'-01')->daysInMonth;
        $weeks = range(1, (int) ceil($daysInMonth / 7));

        return response()->json(ChartData::fromRows($rows, 'week', $weeks));
    }
}
