<?php

namespace App\Services;

use App\Enums\OrderStatuses;
use App\Exceptions\CantUpdateAlreadyApproved;
use App\Exceptions\OrderRequesterCantUpdateException;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use App\QueryFilter;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {}

    public function getAllOrders(QueryFilter $filters): array
    {
        return $this->orderRepository->all($filters);
    }

    public function getOrderById(string $id): ?Order
    {
        $order = $this->orderRepository->get($id);

        return $order;
    }

    public function createOrder(array $data): Order
    {
        return $this->orderRepository->create($data);
    }

    public function updateOrder(string $id, array $data): bool
    {
        $order = $this->getOrderById($id);

        if (!$order->canUserUpdateOrder()) {
            throw new OrderRequesterCantUpdateException();
        }

        return $this->orderRepository->update($id, $data);
    }

    public function changeStatus(string $id, string $status): bool
    {
        $order = $this->getOrderById($id);

        if (!$order->canUserUpdateOrder()) {
            throw new OrderRequesterCantUpdateException();
        }

        return $this->updateOrder($id, [
            'status' => $status
        ]);
    }

    public function deleteOrder(string $id): bool
    {
        return $this->orderRepository->delete($id);
    }

    public function cancelOrder(string $id): bool
    {
        return $this->changeStatus($id, OrderStatuses::CANCELLED->value);
    }
}
