<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Transaction;
use App\Seller;

class Product extends Model
{
    //Constantes para el atributo "status" del producto
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';


    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status', // Se define por constante: available o unavaiable
        'image',
        'seller_id',
    ];

    //True if available, False if unavailable
    public function isAvailable() 
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }





}
