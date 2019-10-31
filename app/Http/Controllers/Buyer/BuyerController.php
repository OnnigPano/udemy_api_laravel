<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Buyer as BuyerResource;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:read-general')->except('show');
        $this->middleware('can:view,buyer')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();
        //::has Filtra los usuarios que tengan una relacion transactions, serian buyers
        $compradores = Buyer::has('transactions')->get();
      
        return $this->resourceCollection($compradores);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comprador = Buyer::has('transactions')->findOrFail($id);

        return new BuyerResource($comprador);
    }

}
