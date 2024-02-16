<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'providers_categories');
        
    }
    public function rules($id = null)
    {
      $id = $id ?? request()->route('id');
      $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
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
