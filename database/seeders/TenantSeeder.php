<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Super Admin (Sin tenant)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@micartadig.com',
            'password' => Hash::make('password'),
            'tenant_id' => null,
            'email_verified_at' => now(),
        ]);

        // 2. Crear un Tenant de prueba (Pollería)
        $tenant1 = Tenant::create([
            'name' => 'Pollería El Gordo',
            'subdomain' => 'polleria',
            'address' => 'Av. Los Pollos 123',
            'whatsapp' => '51999999999',
            'schedule' => 'Lunes a Domingo 12pm a 11pm',
            'is_active' => true,
        ]);

        app()->instance('tenant_id', $tenant1->id);

        User::create([
            'name' => 'Dueño Polleria',
            'email' => 'dueno@polleria.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant1->id,
            'email_verified_at' => now(),
        ]);

        $catPollos = Category::create(['tenant_id' => $tenant1->id, 'name' => 'Pollos a la Brasa', 'order_position' => 1]);
        $catBebidas = Category::create(['tenant_id' => $tenant1->id, 'name' => 'Bebidas', 'order_position' => 2]);

        Product::create([
            'tenant_id' => $tenant1->id,
            'name' => '1/4 de Pollo',
            'description' => 'Un cuarto de pollo con papas fritas y ensalada fresca.',
            'price' => 15.00,
            'order_position' => 1,
        ])->categories()->attach($catPollos->id);
        
        Product::create([
            'tenant_id' => $tenant1->id,
            'name' => '1 Pollo Entero',
            'description' => 'Pollo entero con papas familiares, ensalada y gaseosa 1.5L',
            'price' => 65.00,
            'order_position' => 2,
        ])->categories()->attach($catPollos->id);

        Product::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Inca Kola 1.5L',
            'price' => 10.00,
            'order_position' => 1,
        ])->categories()->attach($catBebidas->id);
        
        app()->forgetInstance('tenant_id'); // Limpiar para futuros seeders
    }
}
