<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'organizations';
    protected $fillable = ['name', 'address', 'contact_num', 'email', 'principle_id'];

}
