<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Service;


class ServiceController extends CrudController
{
  protected $table = 'services';
  protected $modelClass = Service::class;


}
