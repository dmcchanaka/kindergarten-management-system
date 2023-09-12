<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $primarykey = 'p_id';

    protected $fillable = [
        'name',
    ];

    public function getKey(){
        return $this->p_id;
    }
}
