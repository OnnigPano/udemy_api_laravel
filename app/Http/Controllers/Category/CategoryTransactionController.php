<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
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
        $this->allowedAdminAction();
        
        $transactions = $category->products()
            ->whereHas('transactions') //trango los productos WHEREHAS 'que tengan' transactions
            ->with('transactions')     //incluyo la relacion 
            ->get()
            ->pluck('transactions')    //filtro solo las transactions
            ->collapse();               //unir los datos en un solo array
        
        return $this->resourceCollection($transactions);
    }
}
