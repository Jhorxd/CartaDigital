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
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'schedule' => 'nullable|string|max:255',
            'brand_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($tenant->logo && str_contains($tenant->logo, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $tenant->logo);
                Storage::delete($oldPath);
            }
            
            $manager = new ImageManager(new Driver());
            $imageFile = $request->file('logo');
            $filename = Str::random(40) . '.webp';
            
            // Procesar logo: redimensionar a 400px de ancho (logos suelen ser pequeños) y convertir a WebP
            $image = $manager->read($imageFile);
            $image->scale(width: 400);
            $encoded = $image->toWebp(80);
            
            Storage::disk('public')->put('logos/' . $filename, $encoded);
            $validated['logo'] = Storage::url('logos/' . $filename);
        }

        $tenant->update($validated);

        return redirect()->route('tenant.admin.settings.edit')
            ->with('status', 'Configuración actualizada correctamente.');
    }
}
