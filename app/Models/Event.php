<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['description','event_date','org_id','class_room_id'];

    public function class_room(){
        return $this->belongsTo(ClassRoom::class, 'class_room_id', 'id');
    }

    public function organization(){
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }
}
