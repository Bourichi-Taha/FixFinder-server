<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;


class BookingController extends CrudController
{
    protected $table = 'bookings';
    protected $modelClass = Booking::class;

    public function createOne(Request $request)
    {
        $date = $request->input('booking_datetime');
        $carbonDate = Carbon::parse($date);
        $request->merge(['booking_datetime' => $carbonDate]);
        return parent::createOne($request);
    }
    public function updateOne($id,Request $request)
    {
        $date = $request->input('booking_datetime');
        $carbonDate = Carbon::parse($date);
        $request->merge(['booking_datetime' => $carbonDate]);
        return parent::createOne($request);
    }
}
