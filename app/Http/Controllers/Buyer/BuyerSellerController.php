<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
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
        $sellers = $buyer->transactions()->with('product.seller')
        ->get()                     
        ->pluck('product.seller')   //show only sellers inside of products, inside of transaction collection
        ->unique('id') //remove sellers with repeated id 
        ->values(); // recreate de list to remove empty values of repeated sellers

        return $this->showAll($sellers);
    }

}
