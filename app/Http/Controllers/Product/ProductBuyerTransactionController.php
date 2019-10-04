<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse("The buyer cannot be the owner of the product", 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse("The buyer must be a verified user", 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse("The seller must be a verified user", 409);
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse("The product it's not available", 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse("The quantity specified is less than the quantity available", 409);
        }

        // DB::transaction() run a set of operations within a database transaction. 
        // If an exception is thrown within the transaction Closure, the transaction will automatically
        // be rolled back
        return DB::transaction(function() use ($product, $request, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
