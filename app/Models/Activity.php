<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'feature_img_url',
        'org_id',
        'class_room_id',
        'student_id'
    ];

    public function activity_images(){
        return $this->hasMany(ActivityImage::class, 'activity_id', 'id');
    }

    public function class_room(){
        return $this->belongsTo(ClassRoom::class, 'class_room_id', 'id');
    }

    public function organization(){
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
