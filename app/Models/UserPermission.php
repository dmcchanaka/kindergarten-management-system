<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'up_id';

    protected $fillable = [
        'u_tp_id', 'p_id'
    ];

    public function permission(){
        return $this->belongsTo(Permission::class,'p_id','p_id');
    }

    public function getPermissionNameById($id){
        $permission = Permission::find($id);
        return ($permission) ? $permission->name : "-";
    }
}
