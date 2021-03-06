<?php

namespace Tests\Unit;

use Tests\TestCase;
use Artisan;
use Mockery;
use Closure;

class OrderListTest extends TestCase
{
    protected $endpoint = '/orders';
    protected $respStructure = [
        'distance',
        'id',
        'status'
    ];
    protected $mock;
    protected $repo = 'App\Http\Repositories\OrderRepository';
    protected $orders;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'OrdersTableSeeder', '--database' => 'testing']);
        $this->orders = \App\Http\Model\Order::paginate(1);
    }
    /**
     * Clean up the mockery container
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
    /**
     * Mock the class and bind the mocked instance with app
     *
     * @param string $class
     * @return void
     */
    protected function mock($class, Closure $mock = null)
    {
        $mockedInstance = parent::mock($class);
        
        $this->app->instance($class, $mockedInstance);
        return $mockedInstance;
    }
    /**
     * get orders without page and limit
     *
     * @return void
     */
    public function testList200()
    {
        $this->mock($this->repo)->shouldReceive('fetch')->once()->andReturn($this->orders);
        $response = $this->get($this->endpoint);
        $response->assertStatus(200)->assertJsonStructure(['*' => $this->respStructure]);
    }
    /**
     * get orders with page and without limit
     *
     * @return void
     */
    public function testListWithPageOnly200()
    {
        $this->mock($this->repo)->shouldReceive('fetch')->once()->andReturn($this->orders);
        $response = $this->get($this->endpoint."?page=2");
        $response->assertStatus(200)->assertJsonStructure(['*' => $this->respStructure]);
    }
     /**
     * get orders without page and with limit
     *
     * @return void
     */
    public function testListWithLimitOnly200()
    {
        $this->mock($this->repo)->shouldReceive('fetch')->once()->andReturn($this->orders);
        $response = $this->get($this->endpoint."?limit=2");
        $response->assertStatus(200)->assertJsonStructure(['*' => $this->respStructure]);
    }
    /**
     * get orders with page and with limit
     *
     * @return void
     */
    public function testListWithPageAndWithLimit200()
    {
        $this->mock($this->repo)->shouldReceive('fetch')->once()->andReturn($this->orders);
        $response = $this->get($this->endpoint."?page=2&limit=2");
        $response->assertStatus(200)->assertJsonStructure(['*' => $this->respStructure]);
    }
     /**
     * get orders with page minus
     *
     * @return void
     */
    public function testListWithMinusPage400()
    {
        $response = $this->get($this->endpoint."?page=-1");
        $response->assertStatus(400);
    }
     /**
     * get orders with limit minus
     *
     * @return void
     */
    public function testListWithMinusLimit400()
    {
        $response = $this->get($this->endpoint."?limit=-1");
        $response->assertStatus(400);
    }
    /**
     * get orders with page minus and limit minus
     *
     * @return void
     */
    public function testListWithMinusPageAndLimitMinus400()
    {
        $response = $this->get($this->endpoint."?page=-1&limit=-1");
        $response->assertStatus(400);
    }
     /**
     * get orders with valid page but limit minus
     *
     * @return void
     */
    public function testListWithValidPageMinusLimit400()
    {
        $response = $this->get($this->endpoint."?page=2&limit=-1");
        $response->assertStatus(400);
    }
    /**
     * get orders with minus page but valid limit
     *
     * @return void
     */
    public function testListWithMinusPageValidLimit400()
    {
        $response = $this->get($this->endpoint."?page=-2&limit=2");
        $response->assertStatus(400);
    }

    /**
     * get orders with page Alphabet
     *
     * @return void
     */
    public function testListWithAlphabetPage400()
    {
        $response = $this->get($this->endpoint."?page=abc");
        $response->assertStatus(400);
    }
     /**
     * get orders with limit Alphabet
     *
     * @return void
     */
    public function testListWithAlphabetLimit400()
    {
        $response = $this->get($this->endpoint."?limit=abc");
        $response->assertStatus(400);
    }
    /**
     * get orders with page Alphabet and limit Alphabet
     *
     * @return void
     */
    public function testListWithAlphabetPageAndLimitAlphabet400()
    {
        $response = $this->get($this->endpoint."?page=abc&limit=abc");
        $response->assertStatus(400);
    }
     /**
     * get orders with valid page but limit Alphabet
     *
     * @return void
     */
    public function testListWithValidPageAlphabetLimit400()
    {
        $response = $this->get($this->endpoint."?page=2&limit=abc");
        $response->assertStatus(400);
    }
    /**
     * get orders with Alphabet page but valid limit
     *
     * @return void
     */
    public function testListWithAlphabetPageValidLimit400()
    {
        $response = $this->get($this->endpoint."?page=abc&limit=2");
        $response->assertStatus(400);
    }

    /**
     * get orders with page Floating
     *
     * @return void
     */
    public function testListWithFloatingPage400()
    {
        $response = $this->get($this->endpoint."?page=1.5");
        $response->assertStatus(400);
    }
     /**
     * get orders with limit Floating
     *
     * @return void
     */
    public function testListWithFloatingLimit400()
    {
        $response = $this->get($this->endpoint."?limit=1.5");
        $response->assertStatus(400);
    }
    /**
     * get orders with page Floating and limit Floating
     *
     * @return void
     */
    public function testListWithFloatingPageAndLimitFloating400()
    {
        $response = $this->get($this->endpoint."?page=1.5&limit=1.5");
        $response->assertStatus(400);
    }
     /**
     * get orders with valid page but limit Floating
     *
     * @return void
     */
    public function testListWithValidPageFloatingLimit400()
    {
        $response = $this->get($this->endpoint."?page=2&limit=1.5");
        $response->assertStatus(400);
    }
    /**
     * get orders with Floating page but valid limit
     *
     * @return void
     */
    public function testListWithFloatingPageValidLimit400()
    {
        $response = $this->get($this->endpoint."?page=1.5&limit=2");
        $response->assertStatus(400);
    }
    /**
     * get orders with Excessive page number
     *
     * @return void
     */
    public function testListWitAccessivePageNumber()
    {
        $this->mock($this->repo)->shouldReceive('fetch')->once()->andReturn($this->orders);
        $response = $this->get($this->endpoint."?page=500000");
        $response->assertStatus(200)->assertJsonStructure([]);
    }
}
