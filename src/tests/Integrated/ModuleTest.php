<?php

namespace Tests\Integrated;

use Tests\TestCase;
use App\Http\Model\Order;
use App\Http\Exceptions\OrderException;
use Mockery;

class ModuleTest extends TestCase
{
    protected $endpoint = '/orders';
    protected $origin = ["28.704060", "77.102493"];
    protected $dest = ["28.459497", "77.026634"];
    /**
     * testValidFlow
     *
     * @return void
     */
    public function testValidFlow()
    {
        // First check with blank data in database return blank array
        $response = $this->get($this->endpoint);
        $response->assertStatus(200)->assertJsonStructure([]);
        //Create the order
        // $distservice = Mockery::mock(\App\Contracts\RoutingInterface::class);
        // $distservice->shouldReceive('getDistance')
        //     ->with($this->origin[0], $this->origin[1], $this->dest[0], $this->dest[1])
        //     ->once()
        //     ->andReturn(rand(4000,50000));
        // $this->app->instance(\App\Contracts\RoutingInterface::class, $distservice);
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$this->dest]);
        $response_contetnt = $response->getOriginalContent();
        if(isset($response_contetnt['error']) && $response_contetnt['error']=='DISTANCE_API_ERROR')
        {
            $error = isset($response_contetnt['details']) ? $response_contetnt['details'] : 'Please check distance api key in env file';
            echo "\n";
            echo $error;
            return;
        }
        $response->assertStatus(200)->assertJsonStructure([
            'distance',
            'id',
            'status'
        ]);
        //List the order
        $response = $this->get($this->endpoint);
        $response->assertStatus(200)->assertJsonFragment($response_contetnt);
        //Update the order
        $order_id = $response_contetnt['id'];
        $response = $this->patch($this->endpoint.'/'.$order_id,['status'=>'TAKEN']);
        $response->assertStatus(200)->assertJsonStructure(['status']);
        $response->assertJsonFragment(['status'=>'SUCCESS']);
        //Update again the same order
        $response = $this->patch($this->endpoint.'/'.$order_id,['status'=>'TAKEN']);
        $response->assertStatus(409);
        //List the order and check for order status updated or not
        $response_contetnt['status'] = 'TAKEN';
        $response = $this->get($this->endpoint);
        $response->assertStatus(200)->assertJsonFragment($response_contetnt);
    }
}