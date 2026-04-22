<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request, $subdomain)
    {
        $tenantId = app('tenant_id');
        $query = Income::where('tenant_id', $tenantId);

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

        $incomes      = $query->orderByDesc('date')->orderByDesc('id')->paginate(20)->withQueryString();
        $totalFiltered = $query->sum('amount');
        $categories   = Income::predefinedCategories();

        return view('tenant.incomes.index', compact('incomes', 'totalFiltered', 'categories'));
    }

    public function create($subdomain)
    {
        $categories = Income::predefinedCategories();
        return view('tenant.incomes.create', compact('categories'));
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
        Income::create($validated);

        return redirect()->route('tenant.admin.incomes.index')
            ->with('status', '✅ Ingreso registrado correctamente.');
    }

    public function edit($subdomain, Income $income)
    {
        abort_unless($income->tenant_id === app('tenant_id'), 403);
        $categories = Income::predefinedCategories();
        return view('tenant.incomes.edit', compact('income', 'categories'));
    }

    public function update(Request $request, $subdomain, Income $income)
    {
        abort_unless($income->tenant_id === app('tenant_id'), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'category'    => 'nullable|string|max:100',
        ]);

        $income->update($validated);

        return redirect()->route('tenant.admin.incomes.index')
            ->with('status', '✅ Ingreso actualizado correctamente.');
    }

    public function destroy($subdomain, Income $income)
    {
        abort_unless($income->tenant_id === app('tenant_id'), 403);
        $income->delete();

        return redirect()->route('tenant.admin.incomes.index')
            ->with('status', 'Ingreso eliminado.');
    }

    public function report(Request $request, $subdomain)
    {
        $tenantId = app('tenant_id');
        $year     = $request->get('year', now()->year);

        $monthlyTotals = Income::where('tenant_id', $tenantId)
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->groupBy('month')->orderBy('month')->get()->keyBy('month');

        $byCategory = Income::where('tenant_id', $tenantId)
            ->whereYear('date', $year)
            ->selectRaw('COALESCE(category, "Sin categoría") as category, SUM(amount) as total')
            ->groupBy('category')->orderByDesc('total')->get();

        $grandTotal = Income::where('tenant_id', $tenantId)->whereYear('date', $year)->sum('amount');

        $years = Income::where('tenant_id', $tenantId)
            ->selectRaw('YEAR(date) as year')->distinct()->orderByDesc('year')->pluck('year');

        $monthNames = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        return view('tenant.incomes.report', compact(
            'monthlyTotals', 'byCategory', 'grandTotal', 'year', 'years', 'monthNames'
        ));
    }

    public function quickEntry($subdomain)
    {
        $categories = Income::predefinedCategories();
        $lastDate   = Income::where('tenant_id', app('tenant_id'))
                            ->orderByDesc('date')->value('date') ?? now()->toDateString();
        return view('tenant.incomes.quick', compact('categories', 'lastDate'));
    }

    public function bulkStore(Request $request, $subdomain)
    {
        $rows     = $request->input('rows', []);
        $tenantId = app('tenant_id');
        $saved    = 0;

        foreach ($rows as $row) {
            $title  = trim($row['title']  ?? '');
            $amount = trim($row['amount'] ?? '');
            $date   = trim($row['date']   ?? '');

            if ($title === '' && $amount === '') continue;
            if ($title === '' || $amount === '' || $date === '') continue;
            if (!is_numeric($amount) || floatval($amount) <= 0) continue;

            Income::create([
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
            return back()->with('error', 'No se guardó ningún ingreso. Verifica que título, monto y fecha estén completos.');
        }

        return redirect()->route('tenant.admin.incomes.index')
            ->with('status', "✅ {$saved} ingreso(s) guardado(s) correctamente.");
    }
}
