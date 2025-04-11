<?php


use App\Enums\OrderStatuses;
use App\Events\TravelCreated;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\TravelRepositoryInterface;
use App\Models\Order;
use App\Models\Travel;
use App\Models\User;
use App\Services\TravelService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

uses(Tests\TestCase::class);

beforeEach(function () {
    // Set up the test environment, such as global mocks, if needed
});

describe('Travel Service Tests', function (){
    it('dispatches notification event when creating travel with order', function () {
        // Mock the dependencies
        $travelRepository = Mockery::mock(TravelRepositoryInterface::class);
        $orderService = Mockery::mock(OrderServiceInterface::class);

        $user = User::factory()->create();
        Auth::partialMock();
        Auth::shouldReceive('user')->andReturn($user);

        // Mock the Event facade to check if the event is dispatched
        Event::fake();

        // Mock the DB facade transaction
        DB::shouldReceive('transaction')->andReturnUsing(function ($closure) {
            return $closure();
        });

        // Input data for the test
        $travelData = ['destination' => 'Test Destination'];

        // Fake Travel instance to simulate the repository return
        $travel = new Travel([
            'id' => 'test-uuid',
            'destination' => 'Test Destination',
        ]);

        // Set up expectations for the mocks
        $travelRepository->shouldReceive('create')
            ->once()
            ->with($travelData)
            ->andReturn($travel);

        $orderService->shouldReceive('createOrder')
            ->once()
            ->with([
                'orderable_id' => $travel->id,
                'orderable_type' => Travel::class,
                'requester_id' => $user->id,
                'status' => OrderStatuses::REQUESTED,
            ])
            ->andReturn(Mockery::mock(Order::class));

        // Instantiate the service with mocks
        $service = new TravelService($travelRepository, $orderService);

        // Execute the method to be tested
        $createdTravel = $service->createTravelWithOrder($travelData);

        // Check if the TravelCreated event was dispatched
        Event::assertDispatched(TravelCreated::class, function ($event) use ($travel) {
            return $event->travel->id === $travel->id;
        });

        // Check if the method returns the created travel
        expect($createdTravel)->toBe($travel);
    });

    it('should update travel order status', function () {
        // Mock the dependencies
        $travelRepository = Mockery::mock(TravelRepositoryInterface::class);
        $orderService = Mockery::mock(OrderServiceInterface::class);

        // Input data for the test

        $orderData = ['status' => OrderStatuses::APPROVED->value];

        $user = User::factory()->create();
        $travel = Travel::factory()->create();
        $travel->order()->create([
            'requester_id' => $user->id,
        ]);

        // Set up expectations for the mocks
        $travelRepository->shouldReceive('get')
            ->once()
            ->with($travel->id)
            ->andReturn($travel->load('order'));

        $travelRepository->shouldReceive('update')
            ->once()
            ->with($travel->id, ['destination' => 'New Destination', 'order' => $orderData])
            ->andReturn(true);

        $orderService->shouldReceive('updateOrder')
            ->once()
            ->with($travel->order->id, $orderData)
            ->andReturn(true);

        // Instantiate the service with mocks
        $service = new TravelService($travelRepository, $orderService);

        // Execute the method to be tested
        $result = $service->updateTravel($travel->id, [
            'destination' => 'New Destination',
            'order' => $orderData
        ]);

        // Check if the method returns true
        expect($result)->toBe(true);
    });
});

afterEach(function () {
    // Close Mockery after each test
    Mockery::close();
});
