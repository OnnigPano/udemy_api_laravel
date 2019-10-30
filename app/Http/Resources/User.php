<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'esAdministrador' => ($this->admin === 'true'),
            'fechaCreacion' => $this->created_at,
            'fechaActualizacion' => $this->updated_at,
            'fechaEliminacion' => isset($this->deleted_at) ? (string)$this->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $this->id)
                ]
            ]
        ];
    }
}