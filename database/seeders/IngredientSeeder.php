<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create ingredients: beef, cheese and onion
        $ingredients = [
            [
                "name" => "Beef",
                "unit" => "kg",
                "stock_level" => 20,
                "reorder_level_percentage" => 50,
                "reorder_quantity" => 20,
            ],
            [
                "name" => "Cheese",
                "unit" => "kg",
                "stock_level" => 5,
                "reorder_level_percentage" => 50,
                "reorder_quantity" => 5,
            ],
            [
                "name" => "Onion",
                "unit" => "kg",
                "stock_level" => 1,
                "reorder_level_percentage" => 50,
                "reorder_quantity" => 1,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
