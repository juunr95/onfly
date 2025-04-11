<?php

namespace App\Repositories;

use App\Interfaces\TravelRepositoryInterface;
use App\Models\Travel;
use App\QueryFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @implements TravelRepositoryInterface<Travel>
 */
class TravelRepository implements TravelRepositoryInterface
{

    public function all(QueryFilter $filters): array
    {
        return Travel::filter($filters)
            ->with('order')
            ->get()
            ->toArray();
    }

    public function get(int|string $id): ?Travel
    {
        return Travel::query()
            ->with('order')
            ->findOrFail($id);
    }

    public function create(array $data): Travel
    {
        return Travel::create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $travel = $this->get($id);

        return $travel->update($data);
    }

    public function delete(int|string $id): bool
    {
        $travel = $this->get($id);

        return $travel->delete();
    }
}
