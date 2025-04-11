<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponse
{
    public function jsonResponse(array $data, int $status): HttpJsonResponse
    {
        return response()->json($data, $status);
    }

    public function successResponse(array $data): HttpJsonResponse
    {
        return response()->json($data, Response::HTTP_OK);
    }

    public function createdResponse(array $data): HttpJsonResponse
    {
        return $this->jsonResponse($data, Response::HTTP_CREATED);
    }

    public function errorResponse(string $message, int $status): HttpJsonResponse
    {
        return $this->jsonResponse([
            'message' => $message,
        ], $status);
    }

    public function updateResponse(array $data): HttpJsonResponse
    {
        return $this->jsonResponse($data, Response::HTTP_OK);
    }

    public function deleteResponse(array $data): HttpJsonResponse
    {
        return $this->jsonResponse($data, Response::HTTP_OK);
    }
}
