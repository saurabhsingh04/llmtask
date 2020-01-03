<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Http\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/**
 * Order Controller
 */
class OrderController extends Controller
{
    /**
     * Order Service
     *
     * @var OrderService
     */
    private $order;
     /**
     * Validation Service
     *
     * @var ValidationService
     */
    private $validate;
    /**
     * OrderController constructor function
     * @param OrderService $order
     * @param ValidationService $validate
     */
    public function __construct(OrderService $order, ValidationService $validate)
    {
        $this->order = $order;
        $this->validate = $validate;
    }
    /**
     * List the orders
     * @param Request $request
     * 
     * @return JsonResponse orders
     */
    public function list(Request $request)
    {
        $this->validate->listOrderValidate($request);
        return $this->order->get($request)->getCollection();
    }
    /**
     * Create New Order
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate->createOrderValidate($request);
        $order = $this->order->create($request);
        return response()->json([
            "id" => $order->id,
            'status' => $order->status,
            'distance' => $order->distance
        ]);
    }
    /**
     * Update order
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $this->validate->updateOrderValidate($request);
        $this->order->update($request,['status'=>$request->status]);
        return response()->json(["status" => "SUCCESS"]);
    }
}