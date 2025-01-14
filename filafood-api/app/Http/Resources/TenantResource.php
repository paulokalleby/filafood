<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'active'     => $this->active,
            'subscribed' => $this->subscribed('default') ?? false,
            'expiration' => $this->subscriptions('default')->end_at ?? '',
        ];
    }
}
