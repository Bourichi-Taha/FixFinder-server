<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Location;


class LocationController extends CrudController
{
  protected $table = 'locations';
  protected $modelClass = Location::class;


}
