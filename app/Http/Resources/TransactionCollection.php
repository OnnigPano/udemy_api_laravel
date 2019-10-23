<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function originalAttribute($index)
    {
        $attributes = [
                'id' => 'id',
                'cantidad' => 'quantity',
                'comprador' => 'buyer_id',
                'producto' => 'product_id',
                'fechaCreacion' => 'created_at',
                'fechaActualizacion' => 'updated_at',
                'fechaEliminacion' => 'deleted_at',
        ];
    
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
