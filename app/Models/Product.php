<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ["name"];
    
    /**
    * Get the ingredients for the product.
    */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, "product_ingredients")->withPivot("amount", "unit");
    }
}
