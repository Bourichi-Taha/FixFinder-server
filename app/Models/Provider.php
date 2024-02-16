<?php

namespace App\Models;

use DB;

class Provider extends BaseModel
{
    public static $cacheKey = 'providers';

    protected $fillable = [
        'user_id',
        'description',
        'availability_schedule',
        'hourly_rate',
        'average_rating',

        // Add other fields as needed
    ];
    protected static function booted()
    {
        parent::booted();
        static::created(function ($provider) {
            $user = $provider->user;
            $user->givePermission('providers.' . $provider->id . '.read');
            $user->givePermission('providers.' . $provider->id . '.update');
            $user->givePermission('providers.' . $provider->id . '.delete');
        });
        static::deleted(function ($provider) {
            $permissions = Permission::where('name', 'like', 'providers.' . $provider->id . '.%')->get();
            DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
            Permission::destroy($permissions->pluck('id'));
        });
    }
    // Define relationships with other models
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'providers_categories');
    }
    //rules

    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'user_id' => 'required|unique:providers,user_id|exists:users,id',
            'description' => 'required|string|max:255',
            'availability_schedule' => 'required|string|max:255',
            'hourly_rate' => 'float',
            'average_rating' => 'float',
        ];
        if ($id !== null) {
            $rules['user_id'] .= ',' . $id;
        }
        return $rules;
    }
}
