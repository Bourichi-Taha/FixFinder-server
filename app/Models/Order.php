<?php

namespace App\Models;

use DB;

class Order extends BaseModel
{
    public static $cacheKey = 'orders';
    protected $fillable = [
        'client_id', // ID of the client placing the live order
        'service_id', // ID of the service type for the order
        'description', // Description of the order
        'status', // Status of the order (e.g., active, completed, cancelled)
        'title',
        'price',// The price amount or price offered by the client
        // Add any other fields you need for the live order
    ];
    protected $with = [
        'client',
        'service',

    ];
    protected static function booted()
    {
        parent::booted();
        static::created(function ($order) {
            $user = $order->client;
            $user->givePermission('orders.' . $order->id . '.read');
            $user->givePermission('orders.' . $order->id . '.update');
            $user->givePermission('orders.' . $order->id . '.delete');
        });
        static::deleted(function ($order) {
            $permissions = Permission::where('name', 'like', 'orders.' . $order->id . '.%')->get();
            DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
            Permission::destroy($permissions->pluck('id'));
        });
    }
    // Define relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'client_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0', 
            'status' => 'required|in:active,completed,cancelled,confirmed',
            // Add any other validation rules for the fields
        ];
        return $rules;
    }
}
