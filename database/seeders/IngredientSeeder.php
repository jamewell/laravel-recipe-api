<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = IngredientCategory::all();

        $ingredients = [
            // Fruits
            ['name' => 'Apple', 'category' => 'Fruits'],
            ['name' => 'Banana', 'category' => 'Fruits'],
            ['name' => 'Orange', 'category' => 'Fruits'],
            ['name' => 'Strawberry', 'category' => 'Fruits'],
            ['name' => 'Blueberry', 'category' => 'Fruits'],

            // Vegetables
            ['name' => 'Carrot', 'category' => 'Vegetables'],
            ['name' => 'Broccoli', 'category' => 'Vegetables'],
            ['name' => 'Spinach', 'category' => 'Vegetables'],
            ['name' => 'Tomato', 'category' => 'Vegetables'],
            ['name' => 'Cucumber', 'category' => 'Vegetables'],

            // Dairy
            ['name' => 'Milk', 'category' => 'Dairy'],
            ['name' => 'Cheese', 'category' => 'Dairy'],
            ['name' => 'Butter', 'category' => 'Dairy'],
            ['name' => 'Yogurt', 'category' => 'Dairy'],
            ['name' => 'Cream', 'category' => 'Dairy'],

            // Meat
            ['name' => 'Chicken', 'category' => 'Meat'],
            ['name' => 'Beef', 'category' => 'Meat'],
            ['name' => 'Pork', 'category' => 'Meat'],
            ['name' => 'Fish', 'category' => 'Meat'],
            ['name' => 'Turkey', 'category' => 'Meat'],

            // Grains
            ['name' => 'Rice', 'category' => 'Grains'],
            ['name' => 'Wheat', 'category' => 'Grains'],
            ['name' => 'Oats', 'category' => 'Grains'],
            ['name' => 'Corn', 'category' => 'Grains'],
            ['name' => 'Barley', 'category' => 'Grains'],

            // Spices
            ['name' => 'Salt', 'category' => 'Spices'],
            ['name' => 'Pepper', 'category' => 'Spices'],
            ['name' => 'Cinnamon', 'category' => 'Spices'],
            ['name' => 'Cumin', 'category' => 'Spices'],
            ['name' => 'Paprika', 'category' => 'Spices'],

            // Oils
            ['name' => 'Olive Oil', 'category' => 'Oils'],
            ['name' => 'Vegetable Oil', 'category' => 'Oils'],
            ['name' => 'Coconut Oil', 'category' => 'Oils'],
            ['name' => 'Canola Oil', 'category' => 'Oils'],
            ['name' => 'Sesame Oil', 'category' => 'Oils'],

            // Baking
            ['name' => 'Flour', 'category' => 'Baking'],
            ['name' => 'Sugar', 'category' => 'Baking'],
            ['name' => 'Baking Powder', 'category' => 'Baking'],
            ['name' => 'Yeast', 'category' => 'Baking'],
            ['name' => 'Vanilla Extract', 'category' => 'Baking'],

            // Condiments
            ['name' => 'Ketchup', 'category' => 'Condiments'],
            ['name' => 'Mustard', 'category' => 'Condiments'],
            ['name' => 'Mayonnaise', 'category' => 'Condiments'],
            ['name' => 'Soy Sauce', 'category' => 'Condiments'],
            ['name' => 'Hot Sauce', 'category' => 'Condiments'],

            // Seafood
            ['name' => 'Salmon', 'category' => 'Seafood'],
            ['name' => 'Shrimp', 'category' => 'Seafood'],
            ['name' => 'Tuna', 'category' => 'Seafood'],
            ['name' => 'Crab', 'category' => 'Seafood'],
            ['name' => 'Lobster', 'category' => 'Seafood'],

            // Beverages
            ['name' => 'Coffee', 'category' => 'Beverages'],
            ['name' => 'Tea', 'category' => 'Beverages'],
            ['name' => 'Juice', 'category' => 'Beverages'],
            ['name' => 'Soda', 'category' => 'Beverages'],
            ['name' => 'Water', 'category' => 'Beverages'],
        ];

        $categoriesByName = $categories->pluck('name');
        foreach ($ingredients as $ingredientData) {
            if (! $categoriesByName->contains($ingredientData['category'])) {
                continue;
            }

            $ingredientData['category_id'] = $categories->firstWhere('name', $ingredientData['category'])->id;

            Ingredient::firstOrCreate([
                'name' => $ingredientData['name'],
                'category_id' => $ingredientData['category_id'],
            ], [
                'description' => $ingredientData['description'] ?? null,
            ]);
        }
    }
}
