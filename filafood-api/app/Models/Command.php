<?php

namespace App\Models;

use App\Enums\CommandsStatusEnum;
use App\Traits\HasBelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Command extends Model
{
    use HasFactory, HasUuids, HasBelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'payment_id',
        'user_id',
        'identify',
        'number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => CommandsStatusEnum::class,
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'command_item')
            ->withPivot(['id', 'quantity', 'price']);
    }
}
