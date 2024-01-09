<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'u_tp_id',
        'username',
        'password',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'logo_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_type(){
        $this->belongsTo(UserType::class, 'u_tp_id','u_tp_id');
    }

    public function userRole(){
        $userType = UserType::find($this->u_tp_id);
        return ($userType)?$userType->user_type:"-";
    }

    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_teachers', 'teacher_id', 'cls_room_id')->withTimestamps();
    }

    public function childern(){
        return $this->hasMany(Student::class, 'guardian_id', 'id');
    }

    public function unSeenMessages(){
        return $this->hasMany(Chat::class, 'sender_id')
                ->where('seen', 1)
                ->where('receiver_id', auth()->user()->id);
    }
}
