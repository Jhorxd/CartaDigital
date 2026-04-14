<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request, $subdomain)
    {
        $query = Product::with('categories');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $products = $query->orderBy('order_position')->get();
        $categories = Category::orderBy('order_position')->get();

        return view('tenant.products.index', compact('products', 'categories'));
    }

    public function create($subdomain)
    {
        $categories = Category::orderBy('order_position')->get();
        return view('tenant.products.create', compact('categories'));
    }

    public function store(Request $request, $subdomain)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $imageFile = $request->file('image');
            $filename = Str::random(40) . '.webp';
            
            // Procesar imagen: redimensionar a 800px de ancho y convertir a WebP
            $image = $manager->read($imageFile);
            $image->scale(width: 800);
            $encoded = $image->toWebp(75);
            
            Storage::disk('public')->put('products/' . $filename, $encoded);
            $validated['image'] = Storage::url('products/' . $filename);
        }

        $categories = $validated['categories'];
        unset($validated['categories']);

        $product = Product::create($validated);
        $product->categories()->sync($categories);

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
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
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

            $manager = new ImageManager(new Driver());
            $imageFile = $request->file('image');
            $filename = Str::random(40) . '.webp';
            
            // Procesar imagen: redimensionar a 800px de ancho y convertir a WebP
            $image = $manager->read($imageFile);
            $image->scale(width: 800);
            $encoded = $image->toWebp(75);
            
            Storage::disk('public')->put('products/' . $filename, $encoded);
            $validated['image'] = Storage::url('products/' . $filename);
        }

        $categories = $validated['categories'];
        unset($validated['categories']);

        $validated['is_active'] = $request->has('is_active');
        $product->update($validated);
        $product->categories()->sync($categories);

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
