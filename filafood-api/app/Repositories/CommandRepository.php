<?php

namespace App\Repositories;

use App\Models\Command;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CommandRepository
{
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function all(array $filters = [])
    {
        $query =  $this->command
            ->with('user', 'payment', 'products')
            ->when($filters, function (Builder $query) use ($filters) {

                if (isset($filters['identify']))
                    $query->where('identify', 'LIKE', "%{$filters['identify']}%");

                if (isset($filters['number']))
                    $query->whereNumber($filters['number']);

                if (isset($filters['active'])) {
                    if ($filters['active'])  $query->whereActive(true);
                    else  $query->whereActive(false);
                };
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
        return $this->command->with('user', 'payment', 'products')->findOrFail($id);
    }

    public function create(array $data)
    {
        $command = $this->command->whereNumber($data['number'])->whereStatus('Open')->first();

        $products = collect($data['products'])->map(
            fn($item) => array_merge(
                $item,
                Product::select('price')->find($item['product_id'])->toArray()
            )
        );

        if (!$command) {
            $command = $this->command->create([
                'user_id'  => Auth::user()->id,
                'identify' => $this->getIdentify(),
                'number'   => $data['number'],
            ]);
        }

        $order = Order::create([
            'user_id' => Auth::user()->id,
            'number'  => $data['number'],
        ]);

        $command->products()->attach(
            $products->mapWithKeys(
                fn($item) => [
                    $item['product_id'] => [
                        'order_id' => $order->id,
                        'quantity' => $item['quantity'],
                        'price'    => $item['price'],
                    ]
                ]
            )
        );

        return $command;
    }


    private function getIdentify()
    {
        return ($this->command->count() + 1);
    }
}
