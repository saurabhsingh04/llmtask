<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Model\Order;
use App\Http\Exceptions\OrderException;
use Mockery;

class OrderCreateTest extends TestCase
{
    protected $endpoint = '/orders';
    protected $origin = ["28.704060", "77.102493"];
    protected $dest = ["28.459497", "77.026634"];
    /**
     * Valid Input
     *
     * @return void
     */
    public function testValidInput200()
    {
        //Mock distance service
        $distservice = Mockery::mock(\App\Contracts\RoutingInterface::class);
        $distservice->shouldReceive('getDistance')
            ->with($this->origin[0], $this->origin[1], $this->dest[0], $this->dest[1])
            ->once()
            ->andReturn(rand(4000,50000));
        $this->app->instance(\App\Contracts\RoutingInterface::class, $distservice);
        
        //Mock create method of order repo
        $orderRepo = Mockery::mock(\App\Http\Repositories\OrderRepository::class);
        $orderRepo->shouldReceive('create')
            ->once()
            ->andReturn(new \App\Http\Model\Order());
        $this->app->instance(\App\Http\Repositories\OrderRepository::class, $orderRepo);

        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$this->dest]);
        
        $response->assertStatus(200)->assertJsonStructure([
            'distance',
            'id',
            'status'
        ]);
    }
     /**
     * Valid Input but get exception during distance api call
     *
     * @return void
     */
    public function testValidInputButExceptionDistanceApi422()
    {
        $distservice = Mockery::mock(\App\Contracts\RoutingInterface::class);
        $distservice->shouldReceive('getDistance')
            ->with($this->origin[0], $this->origin[1], $this->dest[0], $this->dest[1])
            ->once()
            ->andThrow(new OrderException('DISTANCE_API_ERROR', 422, 'abc'));
        $this->app->instance(\App\Contracts\RoutingInterface::class, $distservice);
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$this->dest]);
        $response->assertStatus(422);
    }
     /**
     * Without origin and destination parameter
     *
     * @return void
     */
    public function testWithoutOriginDest400()
    {
        $response = $this->post($this->endpoint);
        $response->assertStatus(400);
    }
    /**
     * Without origin
     *
     * @return void
     */
    public function testWithoutOrigin400()
    {
        $response = $this->post($this->endpoint,['origin'=>$this->origin]);
        $response->assertStatus(400);
    }
     /**
     * Without destination
     *
     * @return void
     */
    public function testWithoutDestination400()
    {
        $response = $this->post($this->endpoint,['destination'=>$this->dest]);
        $response->assertStatus(400);
    }
    /**
     * Origin not as an array
     *
     * @return void
     */
    public function testOriginNotArray400()
    {
        $response = $this->post($this->endpoint,['origin'=>"123",'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
    /**
     * Dest not as an array
     *
     * @return void
     */
    public function testDestNotArray400()
    {
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>"123"]);
        $response->assertStatus(400);
    }
    /**
     * Origin only with OneParam
     *
     * @return void
     */
    public function testOriginOnlyOneParam400()
    {
        $origin = $this->origin;
        unset($origin[1]);
        $response = $this->post($this->endpoint,['origin'=>$origin,'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
     /**
     * Dest only with OneParam
     *
     * @return void
     */
    public function testDestOnlyOneParam400()
    {
        $dest = $this->dest;
        unset($dest[1]);
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$dest]);
        $response->assertStatus(400);
    }
    /**
     * Origin with Extra parameters
     *
     * @return void
     */
    public function testOriginExtraParam400()
    {
        $origin = $this->origin;
        $origin[2] = "1212";
        $response = $this->post($this->endpoint,['origin'=>$origin,'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
     /**
     * Dest with Extra parameters
     *
     * @return void
     */
    public function testDestExtraParam400()
    {
        $dest = $this->dest;
        $dest[2] = "1212";
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$dest]);
        $response->assertStatus(400);
    }
    /**
     * Origin with Invalid Latitude
     *
     * @return void
     */
    public function testOriginInvalidLatitude400()
    {
        $origin = $this->origin;
        $origin[0] = "11abc";
        $response = $this->post($this->endpoint,['origin'=>$origin,'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
     /**
     * Dest with Invalid Latitude
     *
     * @return void
     */
    public function testDestInvalidLatitude400()
    {
        $dest = $this->dest;
        $dest[0] = "11abc";
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$dest]);
        $response->assertStatus(400);
    }
    /**
     * Origin with Invalid Longitude
     *
     * @return void
     */
    public function testOriginInvalidLongitude400()
    {
        $origin = $this->origin;
        $origin[1] = "11abc";
        $response = $this->post($this->endpoint,['origin'=>$origin,'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
     /**
     * Dest with Invalid Longitude
     *
     * @return void
     */
    public function testDestInvalidLongitude400()
    {
        $dest = $this->dest;
        $dest[1] = "11abc";
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$dest]);
        $response->assertStatus(400);
    }
    /**
     * Origin with float Latitude
     *
     * @return void
     */
    public function testOriginfloatLatitude400()
    {
        $origin = $this->origin;
        $origin[0] = (float)$origin[0];
        $response = $this->post($this->endpoint,['origin'=>$origin,'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
     /**
     * Dest with float Latitude
     *
     * @return void
     */
    public function testDestfloatLatitude400()
    {
        $dest = $this->dest;
        $dest[0] = (float)$dest[0];
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$dest]);
        $response->assertStatus(400);
    }
    /**
     * Origin with float Longitude
     *
     * @return void
     */
    public function testOriginfloatLongitude400()
    {
        $origin = $this->origin;
        $origin[1] = (float)$origin[1];
        $response = $this->post($this->endpoint,['origin'=>$origin,'destination'=>$this->dest]);
        $response->assertStatus(400);
    }
     /**
     * Dest with float Longitude
     *
     * @return void
     */
    public function testDestfloatLongitude400()
    {
        $dest = $this->dest;
        $dest[1] = (float)$dest[1];
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$dest]);
        $response->assertStatus(400);
    }
     /**
     * If origin and dest are same
     *
     * @return void
     */
    public function testSameOrgDest400()
    {
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$this->origin]);
        $response->assertStatus(400);
    }
}