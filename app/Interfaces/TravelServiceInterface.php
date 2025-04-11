<?php

namespace App\Interfaces;

use App\Models\Travel;
use App\QueryFilter;

interface TravelServiceInterface
{
    public function getAllTravels(QueryFilter $filters): array;

    public function getTravelById(string $id): ?Travel;

    public function createTravel(array $data): Travel;

    public function createTravelWithOrder(array $data, array $orderData = null): ?Travel;

    public function updateTravel(string $id, array $data): bool;

    public function deleteTravel(string $id): bool;
}
