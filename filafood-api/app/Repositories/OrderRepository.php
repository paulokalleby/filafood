<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function all(array $filters = [])
    {
        $query =  $this->order
            ->with('user')
            ->whereConfirmed(false)
            ->when($filters, function (Builder $query) use ($filters) {

                if (isset($filters['number']))
                    $query->whereNumber($filters['number']);
            });

        if (
            isset($filters['paginate']) &&
            is_numeric($filters['paginate'])
        )
            return $query->paginate($filters['paginate']);
        else
            return $query->get();
    }

    public function find(string $id)
    {
        return $this->order->with('user', 'products')->findOrFail($id);
    }
}
