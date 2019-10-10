<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        /* This wont work: A buyer has many transactions, so the collection "transactions"
        ** can't find one attribute for a collection. The solution: Eager Loading
        ** $products = $buyer->transactions->product;
        */

        $products = $buyer->transactions()
        ->with('product')
        ->get()
        ->pluck('product'); // Retrieves only the values for the products (exclude values of the transactions)

        return $this->showAll($products);
    }
}
