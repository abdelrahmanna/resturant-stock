<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CheckStoreAvailabilityTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_all_ingredients_are_in_stock()
    {

        // init order controller reflection
        $order_controller_reflection = new ReflectionClass("App\Http\Controllers\OrderController");

        // get checkStockLevel method from controller
        $check_stock_level = $order_controller_reflection->getMethod("checkStockLevel");

        // init order controller
        $order_controller = new OrderController();

        $ingredients =  (object) [
            (object)[
                "id" => 1,
                "name" => "beef",
                "stock_level" => 20.0,
                "unit" => "kg",
                "pivot" => (object)[
                    "product_id" => 1,
                    "ingredient_id" => 1,
                    "amount" => 150.0,
                    "unit" => "g",
                ],
            ],
            (object)[
                "id" => 1,
                "name" => "cheese",
                "stock_level" => 20.0,
                "unit" => "kg",
                "pivot" => (object)[
                    "product_id" => 1,
                    "ingredient_id" => 2,
                    "amount" => 30.0,
                    "unit" => "g",
                ],
            ],
            (object)[
                "id" => 1,
                "name" => "onion",
                "stock_level" => 1,
                "unit" => "kg",
                "pivot" => (object)[
                    "product_id" => 1,
                    "ingredient_id" => 3,
                    "amount" => 1.0,
                    "unit" => "g",
                ],
            ]
        ];
        $product = [
            "quantity" => 2,
        ];

        $result  = $check_stock_level->invoke($order_controller, $ingredients, $product);

        $this->assertTrue(!empty($result));

        $this->assertEquals(0.3, $result[0]["amount_used"]);
        $this->assertEquals(0.06, $result[1]["amount_used"]);
        $this->assertEquals(0.002, $result[2]["amount_used"]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_one_ingredients_is_not_in_stock()
    {

        // init order controller reflection
        $order_controller_reflection = new ReflectionClass("App\Http\Controllers\OrderController");

        // get checkStockLevel method from controller
        $check_stock_level = $order_controller_reflection->getMethod("checkStockLevel");

        // init order controller
        $order_controller = new OrderController();

        $ingredients =  (object) [
            (object)[
                "id" => 1,
                "name" => "beef",
                "stock_level" => 20.0,
                "unit" => "kg",
                "pivot" => (object)[
                    "product_id" => 1,
                    "ingredient_id" => 1,
                    "amount" => 150.0,
                    "unit" => "g",
                ],
            ],
            (object)[
                "id" => 1,
                "name" => "cheese",
                "stock_level" => 20.0,
                "unit" => "kg",
                "pivot" => (object)[
                    "product_id" => 1,
                    "ingredient_id" => 2,
                    "amount" => 30.0,
                    "unit" => "g",
                ],
            ],
            (object)[
                "id" => 1,
                "name" => "onion",
                "stock_level" => 0,
                "unit" => "kg",
                "pivot" => (object)[
                    "product_id" => 1,
                    "ingredient_id" => 3,
                    "amount" => 1.0,
                    "unit" => "g",
                ],
            ]
        ];
        $product = [
            "quantity" => 2,
        ];

        $result  = $check_stock_level->invoke($order_controller, $ingredients, $product);

        $this->assertTrue(empty($result));
    }
}