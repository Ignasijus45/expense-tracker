<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\ExpenseRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
