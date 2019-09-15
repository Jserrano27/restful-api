<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Category extends Model
{
    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
