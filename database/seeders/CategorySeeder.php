<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create categories
        $categories = [
            ['name' => 'Smartphones'],
            ['name' => 'Laptops'],
            ['name' => 'Smartwatches'],
            ['name' => 'Tablets'],
            ['name' => 'Headphones'],
            ['name' => 'Appliances'],
        ];

        Category::insert($categories);
    }
}
