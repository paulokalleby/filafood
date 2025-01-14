<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

use function Illuminate\Events\queueable;

class Tenant extends Model
{
    use HasFactory, HasUuids, Billable;

    protected $fillable = [
        'name',
        'email',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updated(queueable(function (Tenant $customer) {
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));
    }


    public function getAccessEndAttribute()
    {
        if ($this->subscription('default')->ends_at) {

            return Carbon::make($this->subscription('default')->ends_at)->format("d/m/Y à\s H:i:s");
        }

        return false;
    }

    public function commands(): HasMany
    {
        return $this->hasMany(Command::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'tenant_payment');
    }
}
