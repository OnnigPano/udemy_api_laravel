<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transaction;

class Buyer extends User
{
    //método boot para darle formato a la inyección de dependencia
    //utilizando el Scope previamente declarado
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new BuyerScope);
    // }


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
