<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        return $this->belongsToMany(User::class, 'user_friends', 'user_id', 'friend_id');
    }

    public function acceptFriend($friend_id)
    {
        // Adds the friend and then also goes and adds this user to its friend's, friends list.
        $alreadyFriends = DB::table("user_friends")->where('user_id',$this->id)->where('friend_id',$friend_id)->exists();
        if(!$alreadyFriends){
            $this->friends()->attach($friend_id);
            $friend = User::find($friend_id);
            $friend->friends()->attach($this->id);
        }

        DB::table("friend_invitation")->where('user_id',$friend_id)->where('friend_id',$this->id)->delete();
    }

    public function addFriend($friend_id) {
        $userId = Auth::user()->id;
        $record = DB::table("friend_invitation")->where("user_id",$userId)->where("friend_id",$friend_id);
        if(!$record->exists()){
            DB::table("friend_invitation")->insert([
                "user_id" => $userId,
                "friend_id" => $friend_id
            ]);
        }
    }

    public function removeFriend($friend_id)
    {
        // Removes the friend and the reference on the friend object to this object.
        $this->friends()->detach($friend_id);   
        $friend = User::find($friend_id);       
        $friend->friends()->detach($this->id);  
    }

    public static function getAllUsersExcludingFriends($userId){
        return User::fromQuery(
            DB::raw("SELECT * FROM users WHERE id <> :user_id AND id NOT IN (SELECT friend_id FROM user_friends WHERE user_id = :id)"),
            array("user_id"=>$userId,"id"=>$userId)
        );
    }

    public static function getAllUserInvitations($userId){
        return User::fromQuery(
            DB::raw("SELECT * FROM users WHERE id IN (SELECT user_id FROM friend_invitation WHERE friend_id = :id)")
            ,array("id"=>$userId)
        );
    }
}
