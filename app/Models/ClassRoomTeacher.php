<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoomTeacher extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'cls_room_id', 'teacher_id'
    ];
}
