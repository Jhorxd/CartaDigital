<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CartaController extends Controller
{
    public function index()
    {
        $tenant = request()->get('tenant');
        $categories = Category::with(['products' => function($q) {
            $q->orderBy('order_position');
        }])->orderBy('order_position')->get();

        return view('carta.index', compact('tenant', 'categories'));
    }
}
