<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Review;



class ReviewController extends CrudController
{
    protected $table = 'reviews';
    protected $modelClass = Review::class;

}
