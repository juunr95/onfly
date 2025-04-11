<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatuses;
use App\Filters\OrdersFilter;
use App\Http\Requests\Order as OrderRequest;
use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use App\Traits\JsonResponse;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller
{
    use JsonResponse;

    public function __construct(private readonly OrderServiceInterface $orderService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(OrdersFilter $filters): HttpJsonResponse
    {
        $orders = $this->orderService->getAllOrders($filters);

        return $this->successResponse($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest\Create $request): HttpJsonResponse
    {
        $data = $request->validated();

        $order = $this->orderService->createOrder($data);

        return response()->json([
            'data' => $order,
            'message' => 'Order created.',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): HttpJsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        return $this->successResponse([
            'data' => $order,
        ]);
    }

    /**
     * UpdateStatus the specified resource in storage.
     */
    public function update(OrderRequest\Update $request, string $id): HttpJsonResponse
    {
        $data = $request->validated();
        $order = $this->orderService->updateOrder($id, $data);

        return $this->updateResponse([
            'data' => $order,
            'message' => 'Order updated.',
        ]);
    }

    /**
     * UpdateStatus order status.
     */
    public function updateStatus(OrderRequest\UpdateStatus $request, string $id): HttpJsonResponse
    {
        $data = $request->validated('status');
        $order = $this->orderService->changeStatus($id, $data['order']['status']);

        return $this->updateResponse([
            'data' => $order,
            'message' => 'Order status updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): HttpJsonResponse
    {
        $result = $this->orderService->deleteOrder($id);

        return $this->successResponse([
            'message' => $result ? 'Order deleted.' : 'Error deleting order.',
        ]);
    }
}
