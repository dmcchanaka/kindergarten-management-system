<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_id',
        'activity_img_url',
    ];

    public function activity(){
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
}
