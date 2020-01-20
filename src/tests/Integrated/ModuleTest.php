<?php

namespace Tests\Integrated;

use Tests\TestCase;
use App\Http\Model\Order;
use App\Http\Exceptions\OrderException;
use Artisan;

class ModuleTest extends TestCase
{
    protected $endpoint = '/orders';
    protected $origin = ["28.704060", "77.102493"];
    protected $dest = ["28.459497", "77.026634"];

    public function setUp(): void
    {
        parent::setUp();
        $isKeyUpdated = env('DISTANCE_API_KEY');
        if($isKeyUpdated == 'testApiKey')
        {
            echo "Update the valid api key at .env file in src folder by updating the DISTANCE_API_KEY for running integration test";
            exit(1);
        }
    }
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
        $response = $this->post($this->endpoint,['origin'=>$this->origin,'destination'=>$this->dest]);
        $responseContent = $response->getOriginalContent();
        if(isset($responseContent['error']) && $responseContent['error']=='DISTANCE_API_ERROR')
        {
            $error = isset($responseContent['details']) ? $responseContent['details'] : 'Please check distance api key in env file';
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
        $response->assertStatus(200)->assertJsonFragment($responseContent);
        //Update the order
        $order_id = $responseContent['id'];
        $response = $this->patch($this->endpoint.'/'.$order_id,['status'=>'TAKEN']);
        $response->assertStatus(200)->assertJsonStructure(['status']);
        $response->assertJsonFragment(['status'=>'SUCCESS']);
        //Update again the same order
        $response = $this->patch($this->endpoint.'/'.$order_id,['status'=>'TAKEN']);
        $response->assertStatus(409);
        //List the order and check for order status updated or not
        $responseContent['status'] = 'TAKEN';
        $response = $this->get($this->endpoint);
        $response->assertStatus(200)->assertJsonFragment($responseContent);
    }
    /**
     * Test with wrong parameters in request
     *
     * @return void
     */
    public function testCreateWithWrongParameters()
    {
        //With Empty Parameters
        $emptyParams = [
            'origin' => ['28.704060', ''],
            'destination' => ['','77.391029']
        ];

        $response = $this->post($this->endpoint, $emptyParams);
        $response->assertStatus(400);
        // With Invalid Origin destination
        $wrongParams = [
            'origin' => ['28.704060', '200'],
            'destination1' => ['200','77.391029']
        ];
        $response = $this->post($this->endpoint, $wrongParams);
        $response->assertStatus(400);
        //With Extra parameters and invalid longitude in origin
        $extraParams = [
            'origin' => ['44.968046', 'abcd', '44.345565'],
            'destination' => ['27.535517','78.455677']
        ];

        $response = $this->post($this->endpoint, $extraParams);
        $response->assertStatus(400);
    }

    /**
     * Update order test
     *
     * @return void
     */
    public function testOrderUpdate()
    {
        //Create Order
        $params = [
            'origin' => $this->origin,
            'destination' => $this->dest
        ];
        $response = $this->post($this->endpoint, $params);
        $responseContent = $response->getOriginalContent();
        
        $orderId = $responseContent['id'];
        //Update the order with wrong parameter or wrong order id
        $response = $this->patch($this->endpoint.'/'. $orderId,  ['stat' => 'TAKEN']);
        $response->assertStatus(400);
        $response = $this->patch($this->endpoint.'/'. $orderId,  ['status' => 'PICKED']);
        $response->assertStatus(400);
        $response = $this->patch($this->endpoint.'/'. $orderId,  ['status' => '']);
        $response->assertStatus(400);
        $response = $this->patch($this->endpoint.'/abc',  ['status' => 'TAKEN']);
        $response->assertStatus(400);
        $response = $this->patch($this->endpoint.'/123456',  ['status' => 'TAKEN']);
        $response->assertStatus(404);

        //Update the order with status taken
        $response = $this->patch($this->endpoint.'/'. $orderId, ['status' => 'TAKEN']);
        $responseContent = $response->getOriginalContent();
        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $responseContent);
        
        //Update the same order again with status taken
        $response = $this->patch($this->endpoint.'/'. $orderId, ['status' => 'TAKEN']);
        $response->assertStatus(409);
        $responseContent = $response->getOriginalContent();
        $this->assertArrayHasKey('error', $responseContent);
    }
    /**
     * List order tests
     *
     * @return void
     */
    public function testOrderListSuccessCases()
    {
        //Test with invalid value of page and limit
        $invalidQueryArray = [
            'page=0&limit=4',
            'page=abc&limit=4',
            'page=1.5&limit=4',
            'page=-3&limit=4',
            'page=2&limit=0',
            'page=2&limit=abc',
            'page=2&limit=1.5',
            'page=2&limit=-3'
        ];
        foreach ($invalidQueryArray as $queryParam) {
            $response = $this->get($this->endpoint.'?'.$queryParam);
            $response->assertStatus(400);
            $responseContent = $response->getOriginalContent();
            $this->assertArrayHasKey('error', $responseContent);
        }
        //Seeding the database with data 
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'OrdersTableSeeder', '--database' => 'testing']);

        //Test with valid Query parameter
        $validQuery = 'page=1&limit=3';
        $response = $this->get($this->endpoint.'?'.$validQuery);
        $responseContent = $response->getOriginalContent();  
        $response->assertStatus(200)->assertJsonStructure(['*' => [
            'distance',
            'id',
            'status'
        ]]);
    }
}