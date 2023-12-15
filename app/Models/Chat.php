<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'seen'
    ];

    protected $appends = ['time_ago'];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function getTimeAgoAttribute(){
        return $this->created_at->diffForHumans();
    }

    public static function getMessagesQueryBetweenTwoUsers($request, $sender, $receiever){
        $query = self::with(['sender','receiver'])->where(function($q) use($request, $sender, $receiever){
            $q->where(function($sub) use ($sender, $receiever){
                $sub->where('sender_id', $sender)
                    ->where('receiver_id', $receiever);
            })
            ->orWhere(function($sub) use ($sender, $receiever){
                $sub->where('receiver_id', $sender)
                    ->where('sender_id', $receiever);
            });
        });
        return $query;
    }
}
