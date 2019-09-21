<?php

namespace App\Providers;

use App\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
        /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Event listener that changes avaibility of a product to unavailable if quantity reaches 0
        Product::updated(function(Product $product) {
            if($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;

                $product->save();
            }
        }); 
    }
       
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
