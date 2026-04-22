<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request, $subdomain)
    {
        $tenantId = app('tenant_id');

        $query = Expense::where('tenant_id', $tenantId);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', date('m', strtotime($request->month)))
                  ->whereYear('date', date('Y', strtotime($request->month)));
        }

        $expenses = $query->orderByDesc('date')->orderByDesc('id')->paginate(20)->withQueryString();

        $totalFiltered = $query->sum('amount');

        $categories = Expense::predefinedCategories();

        return view('tenant.expenses.index', compact('expenses', 'totalFiltered', 'categories'));
    }

    public function create($subdomain)
    {
        $categories = Expense::predefinedCategories();
        return view('tenant.expenses.create', compact('categories'));
    }

    public function store(Request $request, $subdomain)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'category'    => 'nullable|string|max:100',
        ]);

        $validated['tenant_id'] = app('tenant_id');

        Expense::create($validated);

        return redirect()->route('tenant.admin.expenses.index')
            ->with('status', '✅ Gasto registrado correctamente.');
    }

    public function edit($subdomain, Expense $expense)
    {
        abort_unless($expense->tenant_id === app('tenant_id'), 403);
        $categories = Expense::predefinedCategories();
        return view('tenant.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, $subdomain, Expense $expense)
    {
        abort_unless($expense->tenant_id === app('tenant_id'), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'category'    => 'nullable|string|max:100',
        ]);

        $expense->update($validated);

        return redirect()->route('tenant.admin.expenses.index')
            ->with('status', '✅ Gasto actualizado correctamente.');
    }

    public function destroy($subdomain, Expense $expense)
    {
        abort_unless($expense->tenant_id === app('tenant_id'), 403);
        $expense->delete();

        return redirect()->route('tenant.admin.expenses.index')
            ->with('status', 'Gasto eliminado.');
    }

    public function report(Request $request, $subdomain)
    {
        $tenantId = app('tenant_id');

        $year = $request->get('year', now()->year);

        // Resumen mensual del año
        $monthlyTotals = Expense::where('tenant_id', $tenantId)
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Totales por categoría del año
        $byCategory = Expense::where('tenant_id', $tenantId)
            ->whereYear('date', $year)
            ->selectRaw('COALESCE(category, "Sin categoría") as category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $grandTotal = Expense::where('tenant_id', $tenantId)
            ->whereYear('date', $year)
            ->sum('amount');

        $years = Expense::where('tenant_id', $tenantId)
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $monthNames = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        return view('tenant.expenses.report', compact(
            'monthlyTotals', 'byCategory', 'grandTotal', 'year', 'years', 'monthNames'
        ));
    }

    public function quickEntry($subdomain)
    {
        $categories = Expense::predefinedCategories();
        $lastDate = Expense::where('tenant_id', app('tenant_id'))
                           ->orderByDesc('date')->value('date') ?? now()->toDateString();
        return view('tenant.expenses.quick', compact('categories', 'lastDate'));
    }

    public function bulkStore(Request $request, $subdomain)
    {
        $rows = $request->input('rows', []);
        $tenantId = app('tenant_id');
        $saved = 0;

        foreach ($rows as $row) {
            // Ignorar filas completamente vacías
            $title  = trim($row['title']  ?? '');
            $amount = trim($row['amount'] ?? '');
            $date   = trim($row['date']   ?? '');

            if ($title === '' && $amount === '') continue;

            // Validación mínima por fila
            if ($title === '' || $amount === '' || $date === '') continue;
            if (!is_numeric($amount) || floatval($amount) <= 0) continue;

            Expense::create([
                'tenant_id'   => $tenantId,
                'title'       => $title,
                'amount'      => floatval($amount),
                'date'        => $date,
                'category'    => trim($row['category']    ?? '') ?: null,
                'description' => trim($row['description'] ?? '') ?: null,
            ]);
            $saved++;
        }

        if ($saved === 0) {
            return back()->with('error', 'No se guardó ningún gasto. Verifica que título, monto y fecha estén completos.');
        }

        return redirect()->route('tenant.admin.expenses.index')
            ->with('status', "✅ {$saved} gasto(s) guardado(s) correctamente.");
    }
}
