<?php

namespace App\Models;

use DB;

class Review extends BaseModel
{

    public static $cacheKey = 'reviews';


    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'booking_id',
        'rating',
        'review_text',
        // Add other fields as needed
    ];
    protected static function booted()
    {
      parent::booted();
      static::created(function ($review) {
        $user = $review->reviewer;
        $user->givePermission('reviews.' . $review->id . '.read');
        $user->givePermission('reviews.' . $review->id . '.update');
        $user->givePermission('reviews.' . $review->id . '.delete');
      });
      static::deleted(function ($review) {
        $permissions = Permission::where('name', 'like', 'reviews.' . $review->id . '.%')->get();
        DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
        Permission::destroy($permissions->pluck('id'));
      });
    }
    // Define relationships with other models
    public function reviewer()
    {
        return $this->belongsTo(User::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewee()
    {
        return $this->belongsTo(Provider::class);
    }
    //rules
    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'reviewer_id' => 'required|exists:users,id',
            'reviewee_id' => 'required|exists:providers,id',
            'booking_id' => 'required|exists:bookings,id',
            'review_text' => 'required|string|max:255',
            'rating' => 'required|float',
        ];

        return $rules;
    }
}
