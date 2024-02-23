<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Order;


class OrderController extends CrudController
{
    protected $table = 'orders';
    protected $modelClass = Order::class;


}
