<?php

namespace App\Services;

use App\Enums\OrderStatuses;
use App\Events\TravelCancelled;
use App\Events\TravelCreated;
use App\Exceptions\TravelWithNoRequester;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\TravelRepositoryInterface;
use App\Interfaces\TravelServiceInterface;
use App\Models\Travel;
use App\QueryFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

readonly class TravelService implements TravelServiceInterface
{
    public function __construct(
        private TravelRepositoryInterface $travelRepository,
        private OrderServiceInterface  $orderService
    )
    {}

    public function getAllTravels(QueryFilter $filters): array
    {
        return $this->travelRepository->all($filters);
    }

    public function getTravelById(string $id): ?Travel
    {
        $travel = $this->travelRepository->get($id);

        return $travel;
    }

    public function createTravel(array $data): Travel
    {
        return $this->travelRepository->create($data);
    }

    public function createTravelWithOrder(array $data, array $orderData = null): ?Travel
    {
        if (is_null($orderData)) {
            $orderData = [
                'status' => OrderStatuses::REQUESTED,
            ];
        }

        return DB::transaction(function () use ($data, $orderData) {
            $travel = $this->travelRepository->create($data);

            $orderData['orderable_id'] = $travel->id;
            $orderData['orderable_type'] = Travel::class;
            $orderData['requester_id'] = Auth::user()?->getAuthIdentifier();

            if (!isset($orderData['requester_id'])) {
                throw new TravelWithNoRequester('Requester ID is required');
            }

            $this->orderService->createOrder($orderData);

            // Dispatch the event after the order is created
            Event::dispatch(new TravelCreated($travel));

            return $travel;
        });
    }

    public function updateTravel(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $travel = $this->travelRepository->get($id);

            $this->travelRepository->update($id, $data);

            if (array_key_exists('order', $data) && $travel->order) {
                $this->orderService->updateOrder($travel->order->id, $data['order']);
            }

            return true;
        });
    }

    public function deleteTravel(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $travel = $this->travelRepository->get($id);

            if (!$travel) {
                return false;
            }

            // Delete the order if it exists
            if ($travel->order) {
                $this->orderService->deleteOrder($travel->order->id);
            }

            Event::dispatch(new TravelCancelled($travel));

            return $this->travelRepository->delete($id);
        });
    }

    public function cancelTravel(string $id): bool
    {
        $travel = $this->getTravelById($id);

        if ($travel->order->status !== OrderStatuses::APPROVED->value) {
            throw new TravelWithNoRequester('Travel is not in a cancellable state');
        }

        Event::dispatch(new TravelCancelled($travel));

        return $this->updateTravel($id, [
            'order' => [
                'status' => OrderStatuses::CANCELLED->value
            ]
        ]);
    }

    public function acceptTravel(string $id): bool
    {
        $travel = $this->getTravelById($id);

        if ($travel->order->status !== OrderStatuses::REQUESTED->value) {
            throw new TravelWithNoRequester('Travel is not in an acceptable state');
        }

        return $this->updateTravel($id, [
            'order' => [
                'status' => OrderStatuses::APPROVED->value
            ]
        ]);
    }
}
