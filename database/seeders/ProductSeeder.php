<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Crochet Beanie Hat',
                'description' => 'Warm and cozy handmade beanie, perfect for winter. Made with soft acrylic yarn.',
                'price' => 25.99,
                'image' => 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=300',
                'stock' => 5,
                'category' => 'Hats',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Amigurumi Teddy Bear',
                'description' => 'Cute crochet teddy bear, 8 inches tall, safety eyes, perfect gift for kids.',
                'price' => 29.99,
                'image' => 'https://images.unsplash.com/photo-1592198084033-ade6bc5110c8?w=300',
                'stock' => 3,
                'category' => 'Toys',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Flower Bouquet',
                'description' => 'Handmade crochet flowers that never wilt. Beautiful home decor or gift.',
                'price' => 35.50,
                'image' => 'https://images.unsplash.com/photo-1519378056370-7a5a5e9d4c8f?w=300',
                'stock' => 4,
                'category' => 'Decor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Market Tote Bag',
                'description' => 'Eco-friendly crochet shopping bag. Sturdy, stylish, and machine washable.',
                'price' => 32.00,
                'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=300',
                'stock' => 6,
                'category' => 'Bags',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Baby Blanket',
                'description' => 'Soft baby blanket, perfect for newborns. Machine washable, hypoallergenic.',
                'price' => 45.00,
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=300',
                'stock' => 2,
                'category' => 'Baby',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('products')->insert($products);
    }
}