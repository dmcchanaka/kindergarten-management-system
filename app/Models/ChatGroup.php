<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'admin_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_group_users', 'group_id', 'user_id')->withTimestamps();
    }

    public function groupUnSeenMessages($group_id){
        return $this->hasMany(Chat::class, 'group_id')
                ->where('seen', 1)
                ->whereNull('receiver_id')
                ->where('sender_id', '!=' ,auth()->user()->id)
                ->where('group_id', $group_id);
    }
}
