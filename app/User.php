<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // User friends

    public function friends()
    {
        $friends = $this->belongsToMany('App\User', 'user_friends', 'user_id', 'friend_id');
        return $friends;
    }

    public function addFriend($friend_id)
    {
        // Adds the friend and then also goes and adds this user to its friend's, friends list.
        $this->friends()->attach($friend_id);   
        $friend = User::find($friend_id);       
        $friend->friends()->attach($this->id);  
    }

    public function removeFriend($friend_id)
    {
        // Removes the friend and the reference on the friend object to this object.
        $this->friends()->detach($friend_id);   
        $friend = User::find($friend_id);       
        $friend->friends()->detach($this->id);  
    }
}
