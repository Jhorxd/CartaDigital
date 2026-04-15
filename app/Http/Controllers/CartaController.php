<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CartaController extends Controller
{
    public function index()
    {
        $tenant = request()->get('tenant');
        $categories = Category::where('tenant_id', $tenant->id)->with(['products' => function($q) {
            $q->where('is_active', true)->orderBy('order_position');
        }])->orderBy('order_position')->get();

        if ($tenant->business_type === 'boutique') {
            return view('carta.boutique.index', compact('tenant', 'categories'));
        }
        if ($tenant->business_type === 'urban') {
            return view('carta.urban.index', compact('tenant', 'categories'));
        }
        if ($tenant->business_type === 'arreglos') {
            return view('carta.arreglos.index', compact('tenant', 'categories'));
        }

        return view('carta.restaurant.index', compact('tenant', 'categories'));
    }
}
