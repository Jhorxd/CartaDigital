<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('owner')->latest()->get();
        $stats = [
            'total' => $tenants->count(),
            'active' => $tenants->where('is_active', true)->count(),
            'inactive' => $tenants->where('is_active', false)->count(),
        ];
        return view('dashboard', compact('tenants', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        
        $tenant = Tenant::create([
            'name' => $request->name,
            'subdomain' => $request->subdomain,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin ' . $tenant->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
        ]);
        
        return redirect()->route('dashboard')->with('status', 'Restaurante y usuario creados correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $tenant->load('owner');
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $owner = User::where('tenant_id', $tenant->id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain,' . $tenant->id,
            'email' => 'required|email|unique:users,email,' . ($owner->id ?? 0),
            'password' => 'nullable|string|min:8',
        ]);
        
        $tenant->update([
            'name' => $request->name,
            'subdomain' => $request->subdomain,
        ]);

        if ($owner) {
            $owner->email = $request->email;
            if ($request->filled('password')) {
                $owner->password = Hash::make($request->password);
            }
            $owner->save();
        } else {
            User::create([
                'name' => 'Admin ' . $tenant->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password'),
                'tenant_id' => $tenant->id,
                'email_verified_at' => now(),
            ]);
        }
        
        return redirect()->route('dashboard')->with('status', 'Información actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // Opcional: Eliminar usuarios asociados
        User::where('tenant_id', $tenant->id)->delete();
        $tenant->delete();

        return redirect()->route('dashboard')->with('status', 'Restaurante eliminado');
    }
}
