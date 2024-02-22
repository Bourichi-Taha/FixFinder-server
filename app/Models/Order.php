<?php

namespace App\Models;



class Orders extends BaseModel
{
    public static $cacheKey = 'orders';
    protected $fillable = [
        'client_id', // ID of the client placing the live order
        'category_id', // ID of the service type for the order
        'description', // Description of the order
        'status', // Status of the order (e.g., active, completed, cancelled)
        // Add any other fields you need for the live order
    ];

    // Define relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
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
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,completed,cancelled',
            // Add any other validation rules for the fields
        ];
        return $rules;
    }
}
