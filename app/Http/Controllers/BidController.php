<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Bid;


class BidController extends CrudController
{
    protected $table = 'bids';
    protected $modelClass = Bid::class;


}
