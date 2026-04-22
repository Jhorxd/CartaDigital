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
    public function index($tenant)
    {
        $tenantId = app('tenant_id');
        $currentTenant = request()->get('tenant');

        if ($currentTenant && $currentTenant->business_type === 'gastos') {
            // Stats exclusivas del panel de gastos/ingresos
            $stats = [
                'expenses_month' => Expense::where('tenant_id', $tenantId)
                                           ->whereMonth('date', now()->month)
                                           ->whereYear('date', now()->year)
                                           ->sum('amount'),
                'expenses_year'  => Expense::where('tenant_id', $tenantId)
                                           ->whereYear('date', now()->year)
                                           ->sum('amount'),
                'expenses_count' => Expense::where('tenant_id', $tenantId)->count(),
                'recent_expenses' => Expense::where('tenant_id', $tenantId)
                                            ->orderByDesc('date')
                                            ->orderByDesc('id')
                                            ->limit(5)
                                            ->get(),
                
                // Nuevas stats de ingresos
                'incomes_month' => Income::where('tenant_id', $tenantId)
                                           ->whereMonth('date', now()->month)
                                           ->whereYear('date', now()->year)
                                           ->sum('amount'),
                'incomes_year'  => Income::where('tenant_id', $tenantId)
                                           ->whereYear('date', now()->year)
                                           ->sum('amount'),
                'incomes_count' => Income::where('tenant_id', $tenantId)->count(),
                'recent_incomes' => Income::where('tenant_id', $tenantId)
                                            ->orderByDesc('date')
                                            ->orderByDesc('id')
                                            ->limit(5)
                                            ->get(),
            ];
            
            $stats['balance_month'] = $stats['incomes_month'] - $stats['expenses_month'];
            $stats['balance_year'] = $stats['incomes_year'] - $stats['expenses_year'];

        } else {
            // Stats de tenants con carta (restaurant, boutique, etc.)
            $stats = [
                'categories'     => Category::where('tenant_id', $tenantId)->count(),
                'products'       => Product::where('tenant_id', $tenantId)->count(),
                'active_products'=> Product::where('tenant_id', $tenantId)->where('is_active', true)->count(),
            ];
        }

        return view('tenant.dashboard', compact('stats'));
    }
}
