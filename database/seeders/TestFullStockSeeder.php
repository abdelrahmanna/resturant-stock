<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class TestFullStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create ingredients with full stocks level
        Ingredient::create([
            "name" => "beef",
            "unit" => "kg",
            "stock_level" => 20,
            "reorder_quantity" => 20,
            "reorder_level_percentage" => 50
        ]);

        Ingredient::create([
            "name" => "cheese",
            "unit" => "kg",
            "stock_level" => 10,
            "reorder_quantity" => 10,
            "reorder_level_percentage" => 50
        ]);

        Ingredient::create([
            "name" => "onion",
            "unit" => "kg",
            "stock_level" => 1,
            "reorder_quantity" => 1,
            "reorder_level_percentage" => 50
        ]);

        // create a product with ingredients
        Product::create([
            "name" => "burger"
        ])->ingredients()->attach([
            // get beef ingredient
            Ingredient::where("name", "beef")->first()->id => [
                "amount" => 150,
                "unit" => "g"
            ],
            // get cheese ingredient
            Ingredient::where("name", "cheese")->first()->id => [
                "amount" => 30,
                "unit" => "g"
            ],
            // get onion ingredient
            Ingredient::where("name", "onion")->first()->id => [
                "amount" => 20,
                "unit" => "g"
            ]
        ]);
    }
}
