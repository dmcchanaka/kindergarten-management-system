<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'cls_room_id',
        'first_name',
        'last_name',
        'phone_number', 
        'email',
        'address'
    ];

    public function organization(){
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function class_room(){
        return $this->belongsTo(ClassRoom::class, 'cls_room_id', 'id');
    }
}
