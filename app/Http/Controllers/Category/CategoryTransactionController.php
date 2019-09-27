<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions') //trango los productos WHEREHAS 'que tengan' transactions
            ->with('transactions')     //incluyo la relacion 
            ->get()
            ->pluck('transactions')    //filtro solo las transactions
            ->collapse();               //unir los datos en un solo array
        
        return $this->showAll($transactions);
    }
}
