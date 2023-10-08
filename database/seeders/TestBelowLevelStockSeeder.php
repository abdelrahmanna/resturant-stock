<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestBelowLevelStockSeeder extends Seeder
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
                "stock_level" => 5,
                "reorder_level_percentage" => 50,
                "reorder_quantity" => 20,
            ],
            [
                "name" => "Cheese",
                "unit" => "kg",
                "stock_level" => 4,
                "reorder_level_percentage" => 50,
                "reorder_quantity" => 10,
            ],
            [
                "name" => "Onion",
                "unit" => "kg",
                "stock_level" => 0.25,
                "reorder_level_percentage" => 50,
                "reorder_quantity" => 1,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            \App\Models\Ingredient::create($ingredient);
        }

        // create a product with ingredients
        \App\Models\Product::create([
            "name" => "burger"
        ])->ingredients()->attach([
            // get beef ingredient
            \App\Models\Ingredient::where("name", "Beef")->first()->id => [
                "amount" => 150,
                "unit" => "g"
            ],
            // get cheese ingredient
            \App\Models\Ingredient::where("name", "Cheese")->first()->id => [
                "amount" => 30,
                "unit" => "g"
            ],
            // get onion ingredient
            \App\Models\Ingredient::where("name", "Onion")->first()->id => [
                "amount" => 20,
                "unit" => "g"
            ],
        ]);
    }
}
