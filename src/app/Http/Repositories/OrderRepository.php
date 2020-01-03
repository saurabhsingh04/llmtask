<?php

namespace App\Http\Repositories;

use App\Http\Model\Order;
/**
 * Orders repository
 */
class OrderRepository
{
  /**
   * Order Model
   *
   * @var Order
   */
  private $order;
  /**
   * OrderRepository constructor function
   *
   * @param Order $order
   */
  public function __construct(Order $order)
  {
    $this->order = $order;
  }

  /**
   * Get Order
   * @param int $limit
   * 
   * @return Collection
   */
  public function fetch(int $limit=5)
  {
    return $this->order->select('id','status', 'distance')->paginate($limit);
  }
  /**
   * Create new order
   *
   * @param array $data
   * @return Order
   */
  public function create(array $data)
  {
    return $this->order->create($data);
  }
  /**
   * Get order by id
   *
   * @param integer $id
   * @return Order
   */
  public function getForUpdate(int $id)
  {
    return $this->order->lockForUpdate()->find($id);
  }
  /**
   * Update order
   *
   * @param Order $order
   * @param array $data
   * 
   * @return Order
   */
  public function updateOrder(Order $order, array $data)
  {
    return $order->update($data);
  }
}