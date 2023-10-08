<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class ProductIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get burger product
        $product = \App\Models\Product::first();

        // get beef ingredient
        $beef = Ingredient::where("name", "beef")->first();
        // get cheese ingredient
        $cheese = Ingredient::where("name", "cheese")->first();
        // get onion ingredient
        $onion = Ingredient::where("name", "onion")->first();

        // attach beef ingredient to product
        $product->ingredients()->attach($beef->id, [
            "amount" => 150,
            "unit" => "g"
        ]);

        // attach cheese ingredient to product
        $product->ingredients()->attach($cheese->id, [
            "amount" => 30,
            "unit" => "g"
        ]);

        // attach onion ingredient to product
        $product->ingredients()->attach($onion->id, [
            "amount" => 20,
            "unit" => "g"
        ]);
    }
}
