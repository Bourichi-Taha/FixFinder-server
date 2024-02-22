<?php

namespace App\Models;



class Service extends BaseModel
{
    public static $cacheKey = 'services';
    protected $fillable = [
        'name', // name of the service
        'category_id', // ID of the category for the service
        'description', // Description of the order
        'price', // minimum price of a service
        // Add any other fields you need for the service
    ];
    protected $with = [
        'category'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'name' => 'required|unique:services,name',
            'category_id' => 'required|exists:categories,id',
            'description' => 'string|max:255|nullable',
            'price' => 'required|numeric|min:0',
            // Add any other validation rules for the fields
        ];
        return $rules;
    }
}
