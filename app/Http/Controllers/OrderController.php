<?php

namespace App\Http\Controllers;

use App\Mail\IngredientRunningLow;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreOrderRequest;

use App\Models\Order;
use App\Models\Product;


class OrderController extends Controller
{

    private $conversion_factors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conversion_factors =
            [
                "kg" => [
                    "g" => 1000,
                    "kg" => 1,
                ],
                "g" => [
                    "g" => 1,
                    "kg" => 0.001,
                ]
            ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        // validate request
        $validated = $request->validated();

        if (!$validated) {
            return response()->json([
                "message" => "Invalid request"
            ], 400);
        }

        $productsOutOfStock = [];
        // create order products
        foreach ($request->products as $product) {
            // get product from db
            $product_model = Product::find($product["product_id"]);
            // get ingredients for each product
            $ingredients = $product_model->ingredients->keyBy("id");

            // check if there is enough stock for each ingredient
            $ingredients_in_hand
                = $this->checkStockLevel($ingredients, $product);

            $order = null;
            if (!empty($ingredients_in_hand)) {
                // create order
                if (!$order) {
                    $order = Order::create();
                }

                // attach product to order
                $order->products()->attach($product["product_id"], ["quantity" => $product["quantity"]]);

                // update stock level
                $this->updateStockLevel($ingredients_in_hand, $ingredients);
            } else {
                $productsOutOfStock[] = $product_model->toArray();
            }
        }
        $message = count($productsOutOfStock) == 0 ? "Order placed successfully" :
            "Order placed successfully but some products are out of stock";
        return response()->json([
            "message" => $message,
            "productsOutOfStock" => $productsOutOfStock
        ], 201);
    }

    /**
     * Update the stock level for each ingredient
     * 
     * @param $ingredients_in_hand
     * @param $ingredients
     * 
     * @return void
     */
    private function updateStockLevel($ingredients_in_hand, $ingredients)
    {
        // update stock level
        foreach ($ingredients_in_hand as $ingredient_in_hand) {
            $ingredient = $ingredients[$ingredient_in_hand["ingredient_id"]];
            // check if stock level is below reorder level before the consumption of the order
            $email_sent = $ingredient->isStockBelowReorderLevel();
            // update stock level in db
            $ingredient->stock_level -= $ingredient_in_hand["amount_used"];
            $ingredient->save();
            $ingredient->refresh();

            if (!$email_sent) {
                // check if stock level is below reorder level after the consumption of the order
                $should_send_email = $ingredient->isStockBelowReorderLevel();
                if ($should_send_email) {
                    // send email to admin
                    $email = env("ADMIN_EMAIL");
                    Mail::to($email)->queue(new IngredientRunningLow($ingredient));
                }
            }
        }
    }
    /**
     * Check if there is enough stock for each ingredient
     * 
     * @param $ingredients
     * @param $product
     * @param $ingredients_in_hand
     * 
     * @return bool
     */
    private function checkStockLevel($ingredients, $product)
    {
        $ingredients_in_hand = collect();
        foreach ($ingredients as $ingredient) {
            // check if there is enough stock for the ingredient
            $amount_in_hand = $ingredient->pivot->amount *
                $product["quantity"] *
                $this->conversion_factors[$ingredient->pivot->unit][$ingredient->unit];
            if ($amount_in_hand > $ingredient->stock_level) {
                // @todo: handle not enough stock cases
                return [];
            }

            // push ingredient to ingredients in hand
            $ingredients_in_hand->push([
                "ingredient_id" => $ingredient->id,
                "amount_used" => $amount_in_hand
            ]);
        }

        return $ingredients_in_hand;
    }
}
