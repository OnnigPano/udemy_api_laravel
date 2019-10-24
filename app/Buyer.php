<?php

namespace App;

use App\Transaction;
use App\Scopes\BuyerScope;
use App\Http\Resources\BuyerCollection;

class Buyer extends User
{
    //método boot para darle formato a la inyección de dependencia
    //utilizando el Scope previamente declarado
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new BuyerScope);
    // }

    public $collectionClass = BuyerCollection::class;

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
