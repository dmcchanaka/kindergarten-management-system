<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $primarykey = 'up_id';

    protected $fillable = [
        'u_tp_id', 'p_id'
    ];
}
