<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'titulo' => (string)$this->name,
            'detalles' => (string)$this->description,
            'fechaCreacion' => $this->created_at,
            'fechaActualizacion' => $this->updated_at,
            'fechaEliminacion' => isset($this->deleted_at) ? (string)$this->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $this->id)
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $this->id)
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', $this->id)
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $this->id)
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $this->id)
                ]
            ]
        ];
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
                'id' => 'id',
                'name' => 'titulo',
                'description' => 'detalles',
                'created_at' => 'fechaCreacion',
                'updated_at' => 'fechaActualizacion',
                'deleted_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
