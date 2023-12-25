<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create 10 products
        Product::factory(10)->create();

        // Attach random categories to each product
        Product::all()->each(function ($product) {
            $categories = Category::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $product->categories()->attach($categories);
        });
    }
}
