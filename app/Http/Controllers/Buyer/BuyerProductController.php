<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //Traemos del buyer sus transactions con (WITH()) productos, y pluck() filtra solo los productos sin la transaction
        
        $products = $buyer->transactions()
                          ->with('product')
                          ->get()
                          ->pluck('product');  
                          
        //dd($products);

        return $this->showAll($products);
    }
}