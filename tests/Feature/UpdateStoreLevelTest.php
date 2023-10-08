<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Database\Seeders\TestFullStockSeeder;
use Database\Seeders\TestBelowLevelStockSeeder;
use Database\Seeders\TestStockRunningLowSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Product;
use App\Mail\IngredientRunningLow;

class UpdateStoreLevelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test stock level is updated after order is placed
     *
     * @return void
     */
    public function test_update_stock_level()
    {
        $this->seed(TestFullStockSeeder::class);

        $this->put("/api/orders", [
            "products" => [
                [
                    "product_id" => Product::first()->id,
                    "quantity" => 1
                ]
            ]
        ]);

        $this->assertDatabaseCount("orders", 1);

        $this->assertDatabaseHas("ingredients", [
            "name" => "beef",
            "stock_level" => 19.850
        ]);

        $this->assertDatabaseHas("ingredients", [
            "name" => "cheese",
            "stock_level" => 9.970
        ]);

        $this->assertDatabaseHas("ingredients", [
            "name" => "onion",
            "stock_level" => 0.980
        ]);
    }

    /** 
     * @test place order after the stock level is below 50%  to assert no mail is sent
     * 
     * @return void
     */
    public function test_stock_level_below_50()
    {
        Mail::fake();
        $this->seed(TestBelowLevelStockSeeder::class);

        $this->put("/api/orders", [
            "products" => [
                [
                    "product_id" => Product::first()->id,
                    "quantity" => 2
                ]
            ]
        ]);

        Mail::assertNothingQueued();
        Mail::assertNothingSent();
    }


    /** 
     * @test stock level is below 50% for the first time to assert mail was queued
     * 
     * @return void
     */
    public function test_stock_level_reaches_50_percent_for_the_first_time()
    {
        Mail::fake();
        $this->seed(TestStockRunningLowSeeder::class);

        $this->put("/api/orders", [
            "products" => [
                [
                    "product_id" => Product::first()->id,
                    "quantity" => 2
                ]
            ]
        ]);

        Mail::assertQueued(IngredientRunningLow::class);
    }
}
