<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Seller extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'nombre' => (string)$this->name,
            'correo' => (string)$this->email,
            'esVerificado' => (int)$this->verified,
            'fechaCreacion' => $this->created_at,
            'fechaActualizacion' => $this->updated_at,
            'fechaEliminacion' => isset($this->deleted_at) ? (string)$this->deleted_at : null,


            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $this->id)
                ],
                [
                    'rel' => 'seller.categories',
                    'href' => route('sellers.categories.index', $this->id)
                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index', $this->id)
                ],
                [
                    'rel' => 'seller.buyers',
                    'href' => route('sellers.buyers.index', $this->id)
                ],
                [
                    'rel' => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $this->id)
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $this->id)
                ]
            ]
        ];        
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
                'id' => 'id',
                'name' => 'nombre',
                'email' => 'correo',
                'verified' => 'esVerificado',
                'created_at' => 'fechaCreacion',
                'updated_at' => 'fechaActualizacion',
                'deleted_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
