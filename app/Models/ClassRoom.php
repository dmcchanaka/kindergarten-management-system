<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name', 'phone_number', 'email'
    ];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_room_teachers', 'cls_room_id', 'teacher_id')->withTimestamps();
    }
}
