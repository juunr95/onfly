<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\RepositoryInterface;
use App\Models\Order;
use App\QueryFilter;

/**
 * @implements RepositoryInterface<Order>
 */
class OrderRepository implements OrderRepositoryInterface
{

    /**
     * @return array
     */
    public function all(QueryFilter $filters): array
    {
        return Order::filter($filters)->get()->toArray();
    }

    /**
     * @return Order|null
     */
    public function get(string|int $id): ?Order
    {
        return Order::findOrFail($id);
    }

    /**
     * @return Order
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * @return Order|null
     */
    public function update(string|int $id, array $data): true
    {
        $order = $this->get($id);

        return $order->update($data);
    }

    /**
     * @return bool
     */
    public function delete(string|int $id): bool
    {
        $order = $this->get($id);

        if (!$order) {
            return false;
        }

        return $order->delete();
    }
}
