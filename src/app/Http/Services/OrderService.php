<?php

namespace App\Http\Services;

use App\Http\Model\Order;
use App\Http\Repositories\OrderRepository;
use App\Http\Exceptions\OrderException;
use Illuminate\Http\Request;
use App\Contracts\RoutingInterface;
/**
 * Orders services functions
 */
class OrderService
{
    /**
     * Order repo
     *
     * @var OrderRepository
     */
    private $order;
    /**
     * Order repo
     *
     * @var RoutingInterface
     */
    private $distance;
    /**
     * OrderService constructor function
     *
     * @param OrderRepository $order
     * @param RoutingInterface $distance
     */
	public function __construct(OrderRepository $order, RoutingInterface $distance)
	{
        $this->order = $order ;
        $this->distance = $distance;
	}
    /**
     * Get order function
     * @param Requesr $request
     * 
     * @return Order
     */
	public function get(Request $request)
	{
		return $this->order->fetch($request->limit ?? 5);
    }
    /**
     * Create new order
     * @param Request $request
     * 
     * @return Order
     */
    public function create(Request $request)
    {
        $lat1= $request->origin[0];
        $lang1 = $request->origin[1];
        $lat2 = $request->destination[0];
        $lang2 = $request->destination[1];
        $data = [
            "start_latitude" => $lat1,
            "start_longitude" => $lang1,
            "end_latitude" => $lat2,
            "end_longitude" => $lang2,
            "distance" => $this->distance->getDistance($lat1,$lang1,$lat2,$lang2),
            "status" => "UNASSIGNED"
        ];
        return $this->order->create($data);
    }
    /**
     * Update order
     * @param Request $request
     * @param array $data
     * 
     * @return Order
     */
    public function update(Request $request, array $data)
    {
        return $this->order->updateOrder($request->id, $data);
    }
}