<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            'cantidad' => (int)$this->quantity,
            'comprador' => (int)$this->buyer_id,
            'producto' => (int)$this->product_id,
            'fechaCreacion' => $this->created_at,
            'fechaActualizacion' => $this->updated_at,
            'fechaEliminacion' => isset($this->deleted_at) ? (string)$this->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $this->id)
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index', $this->id)
                ],
                [
                    'rel' => 'transaction.sellers',
                    'href' => route('transactions.sellers.index', $this->id)
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $this->seller_id)
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $this->product_id)
                ]
            ]
        ];

    }

    public static function transformedAttribute($index)
    {
        $attributes = [
                'id' => 'id',
                'quantity' => 'cantidad',
                'buyer_id' => 'comprador',
                'product_id' => 'producto',
                'created_at' => 'fechaCreacion',
                'updated_at' => 'fechaActualizacion',
                'deleted_at' => 'fechaEliminacion',
        ];
    
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
