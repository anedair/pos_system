<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Notebook',
                'description' => '80-page notebook',
                'price' => 45.00,
                'stock' => 50,
                'image' => null,
                'is_active' => 1,
            ],
            [
                'name' => 'Ballpen',
                'description' => 'Black ballpoint pen',
                'price' => 15.00,
                'stock' => 100,
                'image' => null,
                'is_active' => 1,
            ],
            [
                'name' => 'Pencil',
                'description' => 'HB writing pencil',
                'price' => 10.00,
                'stock' => 80,
                'image' => null,
                'is_active' => 1,
            ],
            [
                'name' => 'Eraser',
                'description' => 'White rubber eraser',
                'price' => 12.00,
                'stock' => 60,
                'image' => null,
                'is_active' => 1,
            ],
            [
                'name' => 'Ruler',
                'description' => '30cm plastic ruler',
                'price' => 20.00,
                'stock' => 40,
                'image' => null,
                'is_active' => 1,
            ],
        ];

        $this->db->table('products')->insertBatch($products);
    }
}
