<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Category;


class CategoryController extends CrudController
{
  protected $table = 'categories';
  protected $modelClass = Category::class;


}
