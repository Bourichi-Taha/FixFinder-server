<?php

namespace App\Models;

use DB;

class Bid extends BaseModel
{
    public static $cacheKey = 'bids';
    protected $fillable = [
        'provider_id', // ID of the provider making the bid
        'order_id', // ID of the live order the bid is for
        'amount', // The bid amount or price offered by the provider
        // Add any other fields you need to track for each bid
    ];
    protected $with = [
        'provider',
        'order'
    ];
    protected static function booted()
    {
        parent::booted();
        static::created(function ($bid) {
            $user = $bid->provider->user;
            $user->givePermission('bids.' . $bid->id . '.read');
            $user->givePermission('bids.' . $bid->id . '.update');
            $user->givePermission('bids.' . $bid->id . '.delete');
        });
        static::deleted(function ($bid) {
            $permissions = Permission::where('name', 'like', 'bids.' . $bid->id . '.%')->get();
            DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
            Permission::destroy($permissions->pluck('id'));
        });
    }
    // Define relationships
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'provider_id' => 'required|exists:providers,id',
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            // Add any other validation rules for the fields
        ];
        return $rules;

    }
}
