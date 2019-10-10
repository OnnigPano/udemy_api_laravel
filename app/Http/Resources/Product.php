<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
            'disponibles' => (string)$this->quantity,
            'estado' => (string)$this->status,
            'imagen' => url("img/$this->image"),
            'vendedor' => (int)$this->seller_id,
            'fechaCreacion' => $this->created_at,
            'fechaActualizacion' => $this->updated_at,
            'fechaEliminacion' => isset($this->deleted_at) ? (string)$this->deleted_at : null,
        ];
    }
}
