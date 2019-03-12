<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $loggedInUser = User::find($request->userId);
        $friends = $loggedInUser->friends()->get();
        return view("user.profile",["user" => $loggedInUser,"friends" => $friends]);
    }

    public function friends(Request $request){
        $loggedInUser = Auth::user();
        $users = User::getAllUsersExcludingFriends($loggedInUser->id);
        $friends = $loggedInUser->friends()->get();
        $invitations =  User::getAllUserInvitations($loggedInUser->id);
        return view("user.friends",["friends" => $friends,"nonfriends" => $users,"invites" => $invitations]);
    }

    public function addFriend(Request $request){
        $user = Auth::user();
        $user->addFriend($request->friend_id);
        $user->save();
    }

    public function acceptFriend(Request $request){
        $user = Auth::user();
        $user->acceptFriend($request->friend_id);
    }

    public function changePassword(Request $request){
        if(Hash::check($request->old_password,Auth::user()->getAuthPassword())){
            $loggedInUser = Auth::user();
            $loggedInUser->password = Hash::make($request->new_password);
            $loggedInUser->save();
            return response()->json(['success' => 'success'], 200);
        }
        return response()->json(['error' => 'invalid'], 401);;
    }

    public function changeDetails(Request $request){
        $loggedInUser = Auth::user();
        $loggedInUser->name = $request->name;
        $loggedInUser->email = $request->email;
        $loggedInUser->save();
        return response()->json(['success' => 'success'], 200);
    }
}