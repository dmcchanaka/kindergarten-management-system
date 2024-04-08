<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDevelopment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'note'
    ];

    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
