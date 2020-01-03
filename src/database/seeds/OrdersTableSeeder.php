<?php

use Illuminate\Database\Seeder;
use App\Http\Model\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        factory(Order::class, 1)->create();
    }
}
