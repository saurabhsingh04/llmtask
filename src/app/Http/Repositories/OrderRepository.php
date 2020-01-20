<?php

namespace App\Http\Repositories;

use App\Http\Model\Order;
use DB;
use App\Http\Exceptions\OrderException;
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
  public function getOrder(int $id)
  {
    $order = $this->order->lockForUpdate()->find($id);
    if(!$order)
    {
        throw new OrderException("ORDER_NOT_FOUND", 404,'Order id not found');  
    }
    return $order;
  }
  /**
   * Update order
   *
   * @param integer $id
   * @param array $data
   * 
   * @return Order
   */
  public function updateOrder(int $id, array $data)
  {
    DB::beginTransaction();
    try {
      $order = $this->getOrder($id);
      $affectedRows = Order::where(['id' => $id,'status'=>'UNASSIGNED'])->update(['status' => 'TAKEN']);
      DB::commit();
      if(!$affectedRows)
      {
          throw new OrderException("ALREADY_TAKEN", 409,'Order is already taken.');
      }
    } catch (OrderException $exec) {
      throw $exec;
    } catch (\Throwable $th) {
      DB::rollback();
      throw new OrderException("Error Processing Request", 422);
    }
    return $order;
  }
}