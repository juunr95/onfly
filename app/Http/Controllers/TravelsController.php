<?php

namespace App\Http\Controllers;

use App\Filters\TravelsFilter;
use App\Http\Requests\Travel as TravelRequest;
use App\Interfaces\TravelServiceInterface;
use App\Traits\JsonResponse;
use Illuminate\Http\JsonResponse as HttpJsonResponse;

class TravelsController extends Controller
{
    use JsonResponse;

    public function __construct(private readonly TravelServiceInterface $travelService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(TravelsFilter $filters): HttpJsonResponse
    {
        $travels = $this->travelService->getAllTravels($filters);

        return $this->successResponse([
            'data' => $travels,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TravelRequest\Create $request): HttpJsonResponse
    {
        $data = $request->validated();

        $travel = $this->travelService->createTravelWithOrder($data);

        return $this->createdResponse([
            'data' => $travel,
            'message' => 'Travel created.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): HttpJsonResponse
    {
        $travel = $this->travelService->getTravelById($id);

        return $this->successResponse([
            'data' => $travel,
        ]);
    }

    /**
     * UpdateStatus the specified resource in storage.
     */
    public function update(TravelRequest\Update $request, string $id): HttpJsonResponse
    {
        $data = $request->validated();
        $travel = $this->travelService->updateTravel($id, $data);

        return $this->updateResponse([
            'data' => $travel,
            'message' => 'Travel updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): HttpJsonResponse
    {
        $result = $this->travelService->deleteTravel($id);

        return $this->successResponse([
            'message' => $result ? 'Travel deleted.' : 'Error while deleting the travel.',
        ]);
    }

    public function cancelTravel(string $id): HttpJsonResponse
    {
        $result = $this->travelService->cancelTravel($id);

        return $this->successResponse([
            'message' => $result ? 'Travel cancelled.' : 'Error while cancelling the travel.',
        ]);
    }

    public function approveTravel(string $id): HttpJsonResponse
    {
        $result = $this->travelService->acceptTravel($id);

        return $this->successResponse([
            'message' => $result ? 'Travel accepted.' : 'Error while accepting the travel.',
        ]);
    }
}
