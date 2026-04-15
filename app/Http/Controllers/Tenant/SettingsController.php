<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function edit($subdomain)
    {
        $tenant = request()->get('tenant');
        return view('tenant.settings.edit', compact('tenant'));
    }

    public function update(Request $request, $subdomain)
    {
        $tenant = request()->get('tenant');

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'nullable|string|max:255',
            'whatsapp'     => 'nullable|string|max:20',
            'schedule'     => 'nullable|string|max:255',
            'brand_color'  => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo'         => 'nullable|image|max:2048',
            'cover_image'  => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            if ($tenant->logo && str_contains($tenant->logo, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $tenant->logo);
                Storage::delete($oldPath);
            }
            
            $manager = new ImageManager(new Driver());
            $imageFile = $request->file('logo');
            $filename = Str::random(40) . '.webp';
            
            // Procesar logo: redimensionar a 400px de ancho y convertir a WebP
            $image = $manager->decode($imageFile->getPathname());
            $image->scale(width: 400);
            $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
            
            Storage::disk('public')->put('logos/' . $filename, (string) $encoded);
            $validated['logo'] = Storage::url('logos/' . $filename);
        }

        if ($request->hasFile('cover_image')) {
            if ($tenant->cover_image && str_contains($tenant->cover_image, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $tenant->cover_image);
                Storage::delete($oldPath);
            }

            $manager = new ImageManager(new Driver());
            $imageFile = $request->file('cover_image');
            $filename = Str::random(40) . '.webp';

            // Portada: ancho 1200px, calidad 85 para balance calidad/peso
            $image = $manager->decode($imageFile->getPathname());
            $image->scale(width: 1200);
            $encoded = $image->encodeUsingFileExtension('webp', quality: 85);

            Storage::disk('public')->put('covers/' . $filename, (string) $encoded);
            $validated['cover_image'] = Storage::url('covers/' . $filename);
        }

        $tenant->update($validated);

        return redirect()->route('tenant.admin.settings.edit')
            ->with('status', 'Configuración actualizada correctamente.');
    }
}
