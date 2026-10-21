<?php

namespace App\Http\Controllers;

use App\Exporters\CsvExporter;
use App\Exporters\JsonExporter;
use App\Models\Category;
use App\Repositories\ExpenseRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseRepositoryInterface $expenses,
    ) {}

    public function index(Request $request): View
    {

        return view('dashboard', [
            'expenses' => $this->expenses->forUser($request->user()),
            'categories' => Category::orderBy('name')->get(),
            'totals' => $this->expenses->totalsByCategory($request->user()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
            'spent_at' => ['required', 'date'],
        ]);

        $this->expenses->create($request->user(), $data);

        return redirect('/dashboard');
    }

    public function export(Request $request, string $format): Response
    {
        $exporter = match ($format) {
            'csv' => app(CsvExporter::class),
            'json' => app(JsonExporter::class),
            default => abort(404),
        };

        $expenses = $this->expenses->forUser($request->user());

        return response($exporter->export($expenses))
            ->header('Content-Type', $exporter->contentType())
            ->header('Content-Disposition', 'attachment; filename="expenses.'.$exporter->extension().'"');
    }
}
