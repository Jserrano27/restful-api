<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['seller_id'] = $seller->id;
        $data['image'] = $request->image->store(''); //default path already defined on config/filesystems

        $newProduct = Product::create($data);

        return $this->showOne($newProduct, 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'image' => 'image'
        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);

        if ($request->has('name')) {

            $product->name = $request->name;
        }

        if ($request->has('description')) {

            $product->description = $request->description;
        }

        if ($request->has('quantity')) {

            $product->quantity = $request->quantity;
        }

        if ($request->hasFile('image')) {

            Storage::delete($product->image);

            $product->image = $request->image->store('');
        }

        if ($request->has('status')) {

            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() == 0) {

                return $this->errorResponse('An available product must have at least one category', 409);
            }
        }

        if ($product->isClean()) {

            return $this->errorResponse('You need to choose a different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        // Storage its a Laravel Facade for managing stored files
        // Delete de image, path already defined in config/filesystems.php 
        Storage::delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    protected function checkSeller(Seller $seller, Product $product){

        if($seller->id != $product->seller_id){

            throw new HttpException(422, 'The specified seller is not the actual owner of this product');
        }
    }
}
