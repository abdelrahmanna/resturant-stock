<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed ingredients
        $this->call(IngredientSeeder::class);
        // seed products
        $this->call(ProductSeeder::class);
        // seed product ingredients
        $this->call(ProductIngredientSeeder::class);
    }
}
