<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Provider;
use Illuminate\Http\Request;


class ProviderController extends CrudController
{
  protected $table = 'providers';
  protected $modelClass = Provider::class;


  public function afterCreateOne($item, $request)
  {
    $item->syncRoles(['provider']);
  }

  public function afterUpdateOne($item, $request)
  {
    $item->syncRoles(['provider']);
  }
}
