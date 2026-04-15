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
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'sizes' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer',
        ]);

        if ($request->filled('sizes')) {
            $validated['sizes'] = array_map('trim', explode(',', $request->sizes));
        } else {
            $validated['sizes'] = null;
        }

        $manager = new ImageManager(new Driver());

        // Imagen principal
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $filename = Str::random(40) . '.webp';
            $image = $manager->decode($imageFile->getPathname());
            $image->scale(width: 800);
            $encoded = $image->encodeUsingFileExtension('webp', quality: 75);
            Storage::disk('public')->put('products/' . $filename, (string) $encoded);
            $validated['image'] = Storage::url('products/' . $filename);
        }

        // Galería de imágenes
        if ($request->hasFile('gallery')) {
            $galleryUrls = [];
            foreach ($request->file('gallery') as $file) {
                $filename = Str::random(40) . '.webp';
                $img = $manager->decode($file->getPathname());
                $img->scale(width: 800);
                $encoded = $img->encodeUsingFileExtension('webp', quality: 75);
                Storage::disk('public')->put('products/gallery/' . $filename, (string) $encoded);
                $galleryUrls[] = Storage::url('products/gallery/' . $filename);
            }
            $validated['gallery'] = $galleryUrls;
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
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'sizes' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer',
        ]);

        if ($request->filled('sizes')) {
            $validated['sizes'] = array_map('trim', explode(',', $request->sizes));
        } else {
            $validated['sizes'] = null;
        }

        $manager = new ImageManager(new Driver());

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe localmente
            if ($product->image && str_contains($product->image, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $product->image);
                Storage::delete($oldPath);
            }

            $imageFile = $request->file('image');
            $filename = Str::random(40) . '.webp';
            $image = $manager->decode($imageFile->getPathname());
            $image->scale(width: 800);
            $encoded = $image->encodeUsingFileExtension('webp', quality: 75);
            Storage::disk('public')->put('products/' . $filename, (string) $encoded);
            $validated['image'] = Storage::url('products/' . $filename);
        }

        // Galería de imágenes (añadir a las existentes o reemplazar)
        if ($request->hasFile('gallery')) {
            $galleryUrls = $product->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $filename = Str::random(40) . '.webp';
                $img = $manager->decode($file->getPathname());
                $img->scale(width: 800);
                $encoded = $img->encodeUsingFileExtension('webp', quality: 75);
                Storage::disk('public')->put('products/gallery/' . $filename, (string) $encoded);
                $galleryUrls[] = Storage::url('products/gallery/' . $filename);
            }
            $validated['gallery'] = $galleryUrls;
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
