<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class GastosTenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name'          => 'Mis Gastos Personales',
            'subdomain'     => 'gastos',
            'is_active'     => true,
            'business_type' => 'gastos',
            'brand_color'   => '#ef4444',
        ]);

        app()->instance('tenant_id', $tenant->id);

        User::create([
            'name'              => 'Propietario Gastos',
            'email'             => 'dueno@gastos.com',
            'password'          => Hash::make('password'),
            'tenant_id'         => $tenant->id,
            'email_verified_at' => now(),
        ]);

        app()->forgetInstance('tenant_id');
    }
}
