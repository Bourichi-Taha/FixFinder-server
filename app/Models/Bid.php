<?php

namespace App\Models;


class Bid extends BaseModel
{
    public static $cacheKey = 'bids';
    protected $fillable = [
        'provider_id', // ID of the provider making the bid
        'order_id', // ID of the live order the bid is for
        'amount', // The bid amount or price offered by the provider
        // Add any other fields you need to track for each bid
    ];

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
