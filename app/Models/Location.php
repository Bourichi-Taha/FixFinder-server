<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends BaseModel
{
    use HasFactory;
    public static $cacheKey = 'locations';

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
    ];


    public function rules($id = null)
    {
      $id = $id ?? request()->route('id');
      $rules = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
      ];
      if ($id !== null) {
        $rules['name'] .= ',' . $id;
      }
      return $rules;
    }

    public function getRules()
    {
        return $this->rules;
    }
}
