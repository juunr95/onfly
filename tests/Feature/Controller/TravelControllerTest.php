<?php

use App\Enums\OrderStatuses;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\AuthHelper;

describe('Travel Controller Tests', function () {
    uses(RefreshDatabase::class);
    uses(AuthHelper::class);

    it('should create travel and return order data', function () {
        $headers = $this->getAuthHeaders();

        $data = [
            'destination' => 'New Travel',
            'departure_date' => '2025-05-05',
            'return_date' => '2025-05-10',
        ];

        $response = $this->withHeaders($headers)->postJson('api/v1/travels', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Travel created.']);
        $this->assertDatabaseHas('orders', ['orderable_id' => $response->json('data.id')]);
    });

    it('should return order data', function () {
        $user = User::factory()->create();
        $travel = Travel::factory()->create();
        $travel->order()->create([
            'requester_id' => $user->id,
        ]);

        $response = $this->getJson("api/v1/travels/{$travel->id}");

        $response->assertStatus(200);
        $this->assertEquals(
            OrderStatuses::REQUESTED->value,
            $response->json('data.order.status')
        );
    });

    it('fetch filtered travels', function () {
        Travel::factory()->count(5)->create(['destination' => 'São Paulo']);
        Travel::factory()->count(3)->create(['destination' => 'Campinas']);

        $filters = ['destination' => 'São Paulo'];

        $response = $this->getJson('api/v1/travels?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    });

    it('create a new travel', function () {
        $headers = $this->getAuthHeaders();

        $data = [
            'destination' => 'New Travel',
            'departure_date' => '2025-05-05',
            'return_date' => '2025-05-10',
        ];

        $response = $this->withHeaders($headers)->postJson('api/v1/travels', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Travel created.']);
        $this->assertDatabaseHas('travels', ['destination' => 'New Travel']);
    });

    it('return validation errors', function () {
        $data = [];

        $response = $this->postJson('api/v1/travels', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['destination', 'departure_date', 'return_date']);
    });

    it('return details of a travel', function () {
        $travel = Travel::factory()->create();

        $response = $this->getJson("api/v1/travels/{$travel->id}");

        $response->assertStatus(200);
        $response->assertJson(['data' => $travel->toArray()]);
    });

    it('return error to a invalid ID', function () {
        $response = $this->getJson('api/v1/travels/9999');

        $response->assertStatus(404);
    });

    it('update an existent travel', function () {
        $travel = Travel::factory()->create();

        $data = [
            'destination' => 'New Travel',
        ];

        $response = $this->putJson("api/v1/travels/{$travel->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Travel updated.']);
        $this->assertDatabaseHas('travels', [
            'id' => $travel->id,
            'destination' => 'New Travel',
        ]);
    });

    it('return error when updating nonexistent travel', function () {
        $data = ['destination' => 'New Destination'];

        $response = $this->putJson('api/v1/travels/9999', $data);

        $response->assertStatus(404);
    });

    it('delete an existent travel', function () {
        $travel = Travel::factory()->create();

        $response = $this->deleteJson("api/v1/travels/{$travel->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Travel deleted.']);
        $this->assertSoftDeleted('travels', ['id' => $travel->id]);
    });

    it('return error when try to delete nonexistent travel', function () {
        $response = $this->deleteJson('api/v1/travels/9999');

        $response->assertStatus(404);
    });
});
