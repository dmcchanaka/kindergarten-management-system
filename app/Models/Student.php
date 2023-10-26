<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'org_id',
        'class_room_id',
        'guardian_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'age', 
        'gender',
        'address',
        'special_notice'
    ];

    public function organization(){
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function class_room(){
        return $this->belongsTo(ClassRoom::class, 'class_room_id', 'id');
    }

    public function guardian(){
        return $this->belongsTo(User::class, 'guardian_id', 'id');
    }
}
