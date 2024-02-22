<?php

namespace App\Models;

use DB;

class Booking extends BaseModel
{
    public static $cacheKey = 'bookings';

    protected $fillable = [
        'client_id',
        'provider_id',
        'service_id',
        'booking_datetime',
        'status',
        // Add other fields as needed
    ];
    protected static function booted()
    {
      parent::booted();
      static::created(function ($booking) {
        $user = $booking->client;
        $user->givePermission('bookings.' . $booking->id . '.read');
        $user->givePermission('bookings.' . $booking->id . '.update');
        $user->givePermission('bookings.' . $booking->id . '.delete');
      });
      static::deleted(function ($booking) {
        $permissions = Permission::where('name', 'like', 'bookings.' . $booking->id . '.%')->get();
        DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
        Permission::destroy($permissions->pluck('id'));
      });
    }
    // Define relationships with other models
    public function client()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    //rules
    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'client_id' => 'required|exists:users,id',
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:categories,id',
            'booking_datetime' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ];

        return $rules;
    }
}
