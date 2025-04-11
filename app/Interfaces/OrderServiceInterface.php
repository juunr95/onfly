<?php

namespace App\Interfaces;

use App\Enums\OrderStatuses;
use App\Models\Order;
use App\QueryFilter;

interface OrderServiceInterface
{
    public function getAllOrders(QueryFilter $filters): array;

    public function getOrderById(string $id): ?Order;

    public function createOrder(array $data): Order;

    public function updateOrder(string $id, array $data): bool;

    public function changeStatus(string $id, string $status): bool;

    public function deleteOrder(string $id): bool;
}
