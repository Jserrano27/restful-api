<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
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

        // Event listener to email welcome email for new users
       User::created(function(User $user) {
           //if fails, retry 5 times the sending every 1 second
           retry(5, function() use ($user) {
               Mail::to($user->email)->send(new UserCreated($user));
            }, 1000);
       });
       
        // Event listener to email verification for new email on updated users
        User::updated(function(User $user) {
            if($user->isDirty('email')){
                retry(5, function() use ($user) {
                    Mail::to($user->email)->send(new UserMailChanged($user));
                }, 1000);
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
