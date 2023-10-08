<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ["name", "unit", "stock_level", "reorder_quantity", "reorder_level_percentage"];
   
    protected $casts = [
        "stock_level" => "float",
        "reorder_quantity" => "float",
        "reorder_level_percentage" => "float"
    ];
    
    /**
     * Get the products that use this ingredient
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, "product_ingredients")->withPivot("amount", "unit");
    }

    /**
     * Calculate if stock level is below reorder level
     */
    public function isStockBelowReorderLevel()
    {
        return $this->stock_level < $this->reorder_quantity * ($this->reorder_level_percentage / 100);
    }
}
