<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Model\Order;

class OrderUpdateTest extends TestCase
{
    protected $endpoint = '/api/orders';

     /**
     * Without Order id
     *
     * @return void
     */
    public function testWithoutOrderId405()
    {
        $response = $this->patch($this->endpoint);
        $response->assertStatus(405);
    }
    /**
     * Without status parameter
     *
     * @return void
     */
    public function testWithoutStatus400()
    {
        $response = $this->patch($this->endpoint.'/4');
        $response->assertStatus(400);
    }
    /**
     * Invalid value of status parameter
     *
     * @return void
     */
    public function testWithInvalidStatus400()
    {
        $response = $this->patch($this->endpoint.'/4',['status'=>'TEST']);
        $response->assertStatus(400);
    }
    /**
     * Invalid value of order id as minus
     *
     * @return void
     */
    public function testWithMinusOrderId400()
    {
        $response = $this->patch($this->endpoint.'/-4',['status'=>'TAKEN']);
        $response->assertStatus(400);
    }
    /**
     * Invalid value of order id as decimal
     *
     * @return void
     */
    public function testWithDecimalOrderId400()
    {
        $response = $this->patch($this->endpoint.'/4.5',['status'=>'TAKEN']);
        $response->assertStatus(400);
    }
     /**
     * Invalid value of order id as alphabet
     *
     * @return void
     */
    public function testWithAlphabetOrderId400()
    {
        $response = $this->patch($this->endpoint.'/abc',['status'=>'TAKEN']);
        $response->assertStatus(400);
    }
    /**
     * Invalid value of order id 
     * @return void
     */
    public function testWithInvalidtOrderId404()
    {
        $response = $this->patch($this->endpoint.'/1',['status'=>'TAKEN']);
        $response->assertStatus(404);
    }
    /**
     * Status already taken 
     * @return void
     */
    public function testStatusAlreadyTaken409()
    {
        $order = factory(Order::class)->create(['status'=>'TAKEN']);
        $response = $this->patch($this->endpoint.'/'.$order->id,['status'=>'TAKEN']);
        $response->assertStatus(409);
    }
    /**
     * Valid Order id and status
     * @return void
     */
    public function testValidOrderIdAndValidStatus200()
    {
        $order = factory(Order::class)->create(['status'=>'UNASSIGNED']);
        $response = $this->patch($this->endpoint.'/'.$order->id,['status'=>'TAKEN']);
        $response->assertStatus(200);
    }
}