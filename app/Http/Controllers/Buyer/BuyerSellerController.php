<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        /*
            En este caso lo que hacemos es traer las transactions del buyer
            incluyendo los products y a su vez los sellers (con WITH)
            usamos pluck para filtrar los seller de los productos
            unique para que no se repitan los sellers
            y por último values para que las posiciones vacías se eliminen
        */
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();

        return $this->showAll($sellers);
    }
}
