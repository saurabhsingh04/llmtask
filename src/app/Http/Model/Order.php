<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $hidden = ['start_latitude', 'start_longitude', 'end_latitude', 'end_longitude'];
    protected $fillable = ['start_latitude', 'start_longitude', 'end_latitude', 'end_longitude','status','distance'];
}
