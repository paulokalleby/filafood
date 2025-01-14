<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderService
{
    protected $order;

    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    public function getAllOrders(array $filters)
    {
        return $this->order->all($filters);
    }

    public function findOrderById(string $id)
    {
        return $this->order->find($id);
    }
}
