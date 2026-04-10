<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index($subdomain)
    {
        $products = Product::with('category')->orderBy('order_position')->get();
        return view('tenant.products.index', compact('products'));
    }

    public function create($subdomain)
    {
        $categories = Category::orderBy('order_position')->get();
        return view('tenant.products.create', compact('categories'));
    }

    public function store(Request $request, $subdomain)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        Product::create($validated);

        return redirect()->route('tenant.admin.products.index')
            ->with('status', 'Producto creado correctamente.');
    }

    public function edit($subdomain, Product $product)
    {
        $categories = Category::orderBy('order_position')->get();
        return view('tenant.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $subdomain, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe localmente
            if ($product->image && str_contains($product->image, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $product->image);
                Storage::delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        $validated['is_active'] = $request->has('is_active');
        $product->update($validated);

        return redirect()->route('tenant.admin.products.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy($subdomain, Product $product)
    {
        if ($product->image && str_contains($product->image, '/storage/')) {
            $oldPath = str_replace('/storage/', 'public/', $product->image);
            Storage::delete($oldPath);
        }
        $product->delete();
        return redirect()->route('tenant.admin.products.index')
            ->with('status', 'Producto eliminado.');
    }
}
