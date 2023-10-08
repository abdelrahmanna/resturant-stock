<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIngredient extends Model
{
    use HasFactory;

    protected $fillable = ["amount", "unit_id", "product_id", "ingredient_id"];

    protected $casts = [
        "amount" => "float"
    ];
    
    /**
     * Get the product that owns the product ingredient.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the ingredient that owns the product ingredient.
     */ 
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Get the unit that owns the product ingredient.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
