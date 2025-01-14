<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'identify' => $this->identify,
            'number'   => $this->number,
            'status'   => $this->status,
            'total'    => total($this->products),
            'created'  => Carbon::make($this->created_at)->format('d/m/Y H:i'),
            'updated'  => $this->updated_at->diffForHumans(),
            'payment'  => $this->when(
                $this->relationLoaded('payment') && $this->payment,
                function () {
                    return new PaymentResource($this->payment);
                }
            ),
            'waiter'   => new CommandWaiterResource($this->user),
            'products' => CommandProductResource::collection(
                $this->whenLoaded('products')
            ),
        ];
    }
}
