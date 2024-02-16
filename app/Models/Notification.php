<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'is_read'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');
        $rules = [
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'is_read' => 'required|boolean',
        ];

        return $rules;
    }

    public function getRules()
    {
        return $this->rules;
    }
}
