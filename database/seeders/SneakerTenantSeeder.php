<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class SneakerTenantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear o Actualizar el Tenant
        $tenant = Tenant::updateOrCreate(
            ['subdomain' => 'urban'],
            [
                'name' => 'Urban Sneakers Store',
                'address' => 'Mall de la Ciudad, Local 55',
                'whatsapp' => '51987654321',
                'schedule' => 'Lunes a Domingo: 10am - 10pm',
                'brand_color' => '#f97316',
                'is_active' => true,
                'business_type' => 'urban',
            ]
        );

        // 2. Administrador
        User::updateOrCreate(
            ['email' => 'admin@urban.test'],
            [
                'name' => 'Admin Sneakers',
                'password' => Hash::make('urban123'),
                'tenant_id' => $tenant->id,
                'email_verified_at' => now(),
            ]
        );

        // Limpieza previa solo para este tenant
        Product::where('tenant_id', $tenant->id)->delete();
        Category::where('tenant_id', $tenant->id)->delete();

        // 3. Crear Categorías
        $catRunning = Category::create(['name' => 'Running & Performance', 'tenant_id' => $tenant->id, 'order_position' => 1]);
        $catUrban = Category::create(['name' => 'Urbano & Casual', 'tenant_id' => $tenant->id, 'order_position' => 2]);
        $catLimited = Category::create(['name' => 'Ediciones Limitadas', 'tenant_id' => $tenant->id, 'order_position' => 3]);

        // 4. Crear Productos
        $products = [
            ['name' => 'Nike Air Zoom Pegasus 40', 'price' => 540, 'cat' => $catRunning->id],
            ['name' => 'Adidas Ultraboost Light', 'price' => 620, 'cat' => $catRunning->id],
            ['name' => 'Nike Dunk Low Retro', 'price' => 480, 'cat' => $catUrban->id],
            ['name' => 'Adidas Forum Low', 'price' => 450, 'cat' => $catUrban->id],
            ['name' => 'Jordan 1 Retro High OG', 'price' => 1200, 'cat' => $catLimited->id],
            ['name' => 'Yeezy Boost 350 V2', 'price' => 1500, 'cat' => $catLimited->id],
        ];

        foreach($products as $pData) {
            $p = Product::create([
                'name' => $pData['name'],
                'tenant_id' => $tenant->id,
                'price' => $pData['price'],
                'description' => 'Zapatillas originales de alta calidad.',
                'image' => null,
                'is_active' => true
            ]);
            $p->categories()->attach($pData['cat']);
        }
    }
}
