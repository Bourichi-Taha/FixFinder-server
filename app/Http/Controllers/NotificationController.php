<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Notification;



class NotificationController extends CrudController
{
    protected $table = 'notifications';
    protected $modelClass = Notification::class;

}
