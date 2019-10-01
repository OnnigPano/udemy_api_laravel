<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        //AGREGA AL PRODUCTO UNA CATEGORIA, SI ÉSTA EXISTE NO SE REPITE, NO PISA LAS ANTERIORES Y SE SUMA A LA LISTA
        $product->categories()->syncWithoutDetaching($category->id);
        
        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        //SI EN LA LISTA DE CATEGORIAS DE UN PRODUCTO NO SE ENCUENTRA LA CATEGORIA ESPECIFICADA
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('La categoría especificada no es una categoría de este producto', 404);
        }

        //ES CASO DE Q EXISTa LA ELIMINAMOS CON DETACH -> elimina la relación

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);
    }
}
