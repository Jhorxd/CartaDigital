<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($subdomain)
    {
        $categories = Category::orderBy('order_position')->get();
        return view('tenant.categories.index', compact('categories'));
    }

    public function create($subdomain)
    {
        return view('tenant.categories.create');
    }

    public function store(Request $request, $subdomain)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order_position' => 'nullable|integer',
        ]);

        Category::create($validated);

        return redirect()->route('tenant.admin.categories.index')
            ->with('status', 'Categoría creada correctamente.');
    }

    public function edit($subdomain, Category $category)
    {
        return view('tenant.categories.edit', compact('category'));
    }

    public function update(Request $request, $subdomain, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order_position' => 'nullable|integer',
        ]);

        $category->update($validated);

        return redirect()->route('tenant.admin.categories.index')
            ->with('status', 'Categoría actualizada correctamente.');
    }

    public function destroy($subdomain, Category $category)
    {
        $category->delete();
        return redirect()->route('tenant.admin.categories.index')
            ->with('status', 'Categoría eliminada.');
    }
}
