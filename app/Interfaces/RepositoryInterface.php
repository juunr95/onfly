<?php

namespace App\Interfaces;

use App\QueryFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * @template T of Model
 */
interface RepositoryInterface
{
    /**
     * @return T[]
     */
    public function all(QueryFilter $filters): array;

    /**
     * @return T
     */
    public function get(string|int $id): mixed;

    /**
     * @return T
     */
    public function create(array $data): mixed;

    /**
     * @return T
     */
    public function update(string|int $id, array $data): mixed;

    /**
     * @return bool
     */
    public function delete(string|int $id): bool;
}
