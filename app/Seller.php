<?php

namespace App;

use App\Scopes\SellerScope;
use App\Product;

class Seller extends User
{

    //método boot para darle formato a la inyección de dependencia
    //utilizando el Scope previamente declarado
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new SellerScope);
    // }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
