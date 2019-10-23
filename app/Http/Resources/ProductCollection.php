<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
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
                'titulo' => 'name',
                'detalles' => 'description',
                'disponibles' => 'quantity',
                'estado' => 'status',
                'imagen' => 'image',
                'vendedor' => 'seller_id',
                'fechaCreacion' => 'created_at',
                'fechaActualizacion' => 'updated_at',
                'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
