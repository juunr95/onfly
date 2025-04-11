<?php

namespace Database\Factories;

use App\Enums\OrderStatuses;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'orderable_type' => Travel::class,
            'orderable_id' => Travel::factory(),
            'requester_id' => User::factory(),
            'status' => $this->faker->randomElement(array_column(OrderStatuses::cases(), 'value')),
        ];
    }
}
