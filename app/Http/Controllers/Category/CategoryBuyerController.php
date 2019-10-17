<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
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
    public function index(Category $category)
    {
        $buyers = $category->products()
            ->whereHas('transactions')  //traigo los productos que tengas transactions
            ->with('transactions.buyer')  //los traigo con su relación transactions y dentro buyer
            ->get()
            ->pluck('transactions') //filtro para que traiga solo transacions
            ->collapse()            // uno los datos en una sola collection
            ->pluck('buyer')        // de esa collection filtro por buyer
            ->unique()              //traigo buyers sin repetir
            ->values();              //elimina posiciones vacias

        return $this->showAll($buyers);
    }
}
