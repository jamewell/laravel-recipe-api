<?php

namespace Database\Seeders;

use App\Models\IngredientCategory;
use Illuminate\Database\Seeder;

class IngredientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fruits', 'description' => 'Various fresh fruits'],
            ['name' => 'Vegetables', 'description' => 'Fresh vegetables'],
            ['name' => 'Dairy', 'description' => 'Milk and dairy products'],
            ['name' => 'Meat', 'description' => 'Various meats and poultry'],
            ['name' => 'Grains', 'description' => 'Cereals and grains'],
            ['name' => 'Spices', 'description' => 'Herbs and spices'],
            ['name' => 'Oils', 'description' => 'Cooking oils and fats'],
            ['name' => 'Baking', 'description' => 'Baking ingredients'],
            ['name' => 'Condiments', 'description' => 'Sauces and condiments'],
            ['name' => 'Seafood', 'description' => 'Fish and seafood'],
            ['name' => 'Beverages', 'description' => 'Drinks and beverages'],
        ];

        foreach ($categories as $category) {
            IngredientCategory::firstOrCreate($category);
        }
    }
}
