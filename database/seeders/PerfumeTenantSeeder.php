<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class PerfumeTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Tenant
        $tenant = Tenant::create([
            'name' => 'El Rincón Árabe',
            'subdomain' => 'arabian',
            'logo' => null, // Omit to see the letter logo
            'address' => 'Av. Los Perfumistas 123',
            'whatsapp' => '999999999',
            'schedule' => 'Lunes a Sábado: 10am - 8pm',
            'brand_color' => '#8B5A2B', // A kind of gold/brown / premium look
            'is_active' => true,
            'business_type' => 'boutique',
        ]);

        // 2. Create Admin
        User::create([
            'name' => 'Admin Perfumería',
            'email' => 'admin@arabian.localhost',
            'password' => Hash::make('password123'),
            'tenant_id' => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // 3. Create Categories
        $catMen = Category::create(['tenant_id' => $tenant->id, 'name' => 'Perfumes Masculinos', 'order_position' => 1]);
        $catWomen = Category::create(['tenant_id' => $tenant->id, 'name' => 'Perfumes Femeninos', 'order_position' => 2]);
        $catUnisex = Category::create(['tenant_id' => $tenant->id, 'name' => 'Colección Unisex Premium', 'order_position' => 3]);

        // 4. Create Products
        // Masculinos
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Lattafa Asad 100ml', 'description' => 'Aroma cálido especiado con notas de vainilla, ámbar y maderas. Excelente inspiración.', 'price' => 120.00, 'order_position' => 1, 'is_active' => true])->categories()->attach($catMen->id);
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Afnan 9 PM 100ml', 'description' => 'Dulce, avainillado y seductor. Perfecto para la noche.', 'price' => 165.00, 'order_position' => 2, 'is_active' => true])->categories()->attach($catMen->id);
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Club de Nuit Intense Man', 'description' => 'Cítrico, ahumado y amaderado. El rey de los cumplidos.', 'price' => 180.00, 'order_position' => 3, 'is_active' => true])->categories()->attach($catMen->id);
        
        // Femeninos
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Lattafa Yara 100ml', 'description' => 'Aroma atalcado, dulce con toques de orquídea, heliotropo y vainilla. Muy femenino.', 'price' => 140.00, 'order_position' => 1, 'is_active' => true])->categories()->attach($catWomen->id);
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Club de Nuit Woman 105ml', 'description' => 'Elegancia pura. Rosas, pachulí y notas florales potentes y duraderas en piel.', 'price' => 180.00, 'order_position' => 2, 'is_active' => true])->categories()->attach($catWomen->id);
        
        // Unisex
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Orientica Royal Amber 80ml', 'description' => 'Lujo puro. Notas frutales dulces envueltas en almizcle suave y ámbar.', 'price' => 350.00, 'order_position' => 1, 'is_active' => true])->categories()->attach([$catUnisex->id, $catMen->id, $catWomen->id]);
        Product::create(['tenant_id' => $tenant->id, 'name' => 'Lattafa Khamrah 100ml', 'description' => 'Canela, coñac, praliné y vainilla dulce. Sumamente reconfortante.', 'price' => 190.00, 'order_position' => 2, 'is_active' => true])->categories()->attach([$catUnisex->id, $catMen->id, $catWomen->id]);
    }
}
