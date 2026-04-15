<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class ArreglosTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Tenant
        $tenant = Tenant::updateOrCreate(
            ['subdomain' => 'arreglos-florales'],
            [
                'name' => 'Arreglos y Detalles',
                'business_type' => 'arreglos',
                'brand_color' => '#e11d48',
                'whatsapp' => '51900000000'
            ]
        );

        // 2. Create Admin
        User::updateOrCreate(
            ['email' => 'flores@gmail.com'],
            [
                'name' => 'Admin Arreglos',
                'password' => Hash::make('12345678'),
                'tenant_id' => $tenant->id,
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Category
        $category = Category::updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Rosas'],
            ['order_position' => 1]
        );

        // 4. Detailed Rose Products
        $productos = [
            [
                'name' => 'Ramo de 12 Rosas Rojas',
                'price' => 85.00,
                'description' => 'Hermoso ramo de docena de rosas rojas de exportación envueltas en papel decorativo con lazo floral.',
                'order_position' => 1,
            ],
            [
                'name' => 'Ramo de 24 Rosas Rojas Premium',
                'price' => 150.00,
                'description' => 'Espectacular ramo de 2 docenas de rosas rojas seleccionadas, envuelto en papel coreano de lujo con lazo de seda.',
                'order_position' => 2,
            ],
            [
                'name' => 'Caja de Rosas Mixtas (Rojas y Blancas)',
                'price' => 110.00,
                'description' => 'Elegante caja redonda con 18 rosas en combinación de colores clásicos, ideal para aniversarios.',
                'order_position' => 3,
            ],
            [
                'name' => 'Arreglo Dulce Amor en Jarrón',
                'price' => 95.00,
                'description' => 'Arreglo floral de 12 rosas variadas en jarrón de vidrio cristalino con follaje verde de estación.',
                'order_position' => 4,
            ],
            [
                'name' => 'Rosa Eterna en Cúpula de Cristal',
                'price' => 135.00,
                'description' => 'Rosa natural preservada que dura años, presentada en cúpula de vidrio con base de madera. Inspiración encantada.',
                'order_position' => 5,
            ],
            [
                'name' => 'Bouquet de Rosas Rosadas y Astromelias',
                'price' => 75.00,
                'description' => 'Delicado bouquet de 10 rosas rosadas acompañadas de astromelias blancas, perfecto para celebrar un cumpleaños.',
                'order_position' => 6,
            ],
            [
                'name' => 'Corazón de Rosas Rojas',
                'price' => 180.00,
                'description' => 'Arreglo floral compacto en forma de corazón con rosas frescas de exportación sobre base de oasis.',
                'order_position' => 7,
            ]
        ];

        foreach ($productos as $data) {
            $product = Product::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $data['name']],
                [
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'is_active' => true,
                    'order_position' => $data['order_position'],
                    'image' => null 
                ]
            );
            $product->categories()->sync([$category->id]);
        }
    }
}
