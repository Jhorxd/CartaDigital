<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($tenant->logo && str_contains($tenant->logo, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $tenant->logo);
                Storage::delete($oldPath);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = Storage::url($path);
        }

        $tenant->update($validated);

        return redirect()->route('tenant.admin.settings.edit')
            ->with('status', 'Configuración actualizada correctamente.');
    }
}
