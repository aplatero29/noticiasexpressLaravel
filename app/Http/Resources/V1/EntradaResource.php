<?php

namespace App\Http\Resources\V1;

use App\Models\Entrada;
use Illuminate\Http\Resources\Json\JsonResource;

class EntradaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'imagen' => url($this->imagen),
            'autor' => [
                'id' => $this->user->id,
                'nombre' => $this->user->nombre,
                'email' => $this->user->email,
            ],
            'categoria' => [
                'id' => $this->categoria->id,
                'nombre' => $this->categoria->nombre
            ],
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
