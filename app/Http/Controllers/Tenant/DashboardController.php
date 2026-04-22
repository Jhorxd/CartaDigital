<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, $tenant)
    {
        $tenantId = app('tenant_id');
        $currentTenant = request()->get('tenant');

        if ($currentTenant && $currentTenant->business_type === 'gastos') {
            
            // Determinar el mes y año a filtrar (por defecto actual)
            $selectedMonthStr = $request->get('month', now()->format('Y-m'));
            $year  = date('Y', strtotime($selectedMonthStr));
            $month = date('m', strtotime($selectedMonthStr));

            $stats = [
                'selected_month' => $selectedMonthStr,
                'expenses_month' => Expense::where('tenant_id', $tenantId)
                                           ->whereMonth('date', $month)
                                           ->whereYear('date', $year)
                                           ->sum('amount'),
                'expenses_year'  => Expense::where('tenant_id', $tenantId)
                                           ->whereYear('date', $year)
                                           ->sum('amount'),
                'expenses_count' => Expense::where('tenant_id', $tenantId)->count(),
                'recent_expenses' => Expense::where('tenant_id', $tenantId)
                                            ->whereMonth('date', $month)
                                            ->whereYear('date', $year)
                                            ->orderByDesc('date')
                                            ->orderByDesc('id')
                                            ->limit(5)
                                            ->get(),
                
                'incomes_month' => Income::where('tenant_id', $tenantId)
                                           ->whereMonth('date', $month)
                                           ->whereYear('date', $year)
                                           ->sum('amount'),
                'incomes_year'  => Income::where('tenant_id', $tenantId)
                                           ->whereYear('date', $year)
                                           ->sum('amount'),
                'incomes_count' => Income::where('tenant_id', $tenantId)->count(),
                'recent_incomes' => Income::where('tenant_id', $tenantId)
                                            ->whereMonth('date', $month)
                                            ->whereYear('date', $year)
                                            ->orderByDesc('date')
                                            ->orderByDesc('id')
                                            ->limit(5)
                                            ->get(),
            ];
            
            $stats['balance_month'] = $stats['incomes_month'] - $stats['expenses_month'];
            $stats['balance_year'] = $stats['incomes_year'] - $stats['expenses_year'];

        } else {
            $stats = [
                'categories'     => Category::where('tenant_id', $tenantId)->count(),
                'products'       => Product::where('tenant_id', $tenantId)->count(),
                'active_products'=> Product::where('tenant_id', $tenantId)->where('is_active', true)->count(),
            ];
        }

        return view('tenant.dashboard', compact('stats'));
    }
}
