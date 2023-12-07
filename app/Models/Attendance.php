<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'att_date',
        'att_time',
        'approved_at',
        'approved_by',
    ];

    public function student(){
        $this->belongsTo(Student::class, 'student_id','id');
    }

}
