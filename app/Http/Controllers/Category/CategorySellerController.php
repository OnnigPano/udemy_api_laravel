<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
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
        $sellers = $category->products() //traigo productos de la categoria
            ->with('seller')             //traigo los datos incluyendo la relación seller del producto
            ->get()                      //
            ->pluck('seller')            //filtro solo los seller
            ->unique()                   //elimino los repetidos
            ->values();                //elimino posiciones vacías por sellers eliminados repetidos

        return $this->showAll($sellers);
    }
}
