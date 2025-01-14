<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'number'   => $this->number,
            'total'    => total($this->products),
            'confirmed' => $this->confirmed,
            'created'  => Carbon::make($this->created_at)->format('d/m/Y H:i'),
            'updated'  => $this->updated_at->diffForHumans(),
            'waiter'   => new CommandWaiterResource($this->user),
            'products' => CommandProductResource::collection(
                $this->whenLoaded('products')
            ),
        ];
    }
}
