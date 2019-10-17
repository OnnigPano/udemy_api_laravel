<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
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
    public function index()
    {
           //::has Filtra los usuarios que tengan una relacion products, serian sellers
           $vendedores = Seller::has('products')->get();
      
           return response()->json(['data' => $vendedores], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendedor = Seller::has('products')->findOrFail($id);

        return response()->json(['data' => $vendedor], 200);
    }
}
