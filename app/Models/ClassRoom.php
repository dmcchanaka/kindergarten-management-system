<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'org_id', 'name', 'phone_number', 'email'
    ];

    public function organization(){
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_room_teachers', 'cls_room_id', 'teacher_id')->withTimestamps();
    }

    public function students(){
        return $this->hasMany(Student::class, 'class_room_id', 'id');
    }
}
