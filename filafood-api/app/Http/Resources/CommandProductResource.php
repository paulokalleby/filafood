<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->pivot->id,
            'name'     => $this->name,
            'quantity' => $this->pivot->quantity,
            'price'    => $this->pivot->price,
        ];
    }
}
