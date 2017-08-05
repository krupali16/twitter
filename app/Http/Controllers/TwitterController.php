<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use Twitter;
use Socialite;

use Illuminate\Http\Request;

class TwitterController extends Controller
{
    public function index() {
		return view('home');
	}

	public function redirectToProvider() {
		return Socialite::driver('twitter')->redirect();
	}

	public function handleProviderCallback() {

		$user = Socialite::driver('twitter')->user();
		/*$findUser = User::where('twitter_id', $user->getId())->first();
		if($findUser) {
			Auth::login($findUser);	
		} 
		else {
			$newUser = new User;
			$newUser->name = $user->getName();
			$newUser->nickname = $user->getNickname();
			$newUser->twitter_id = $user->getId();
			$newUser->avatar = $user->getAvatar();
			$newUser->save();
			Auth::login($newUser);
		}

		if(Auth::check()) {
			$id = Auth::user()->twitter_id;
			$tweets = Twitter::getUserTimeline(['user_id' => $id, 'count' => 10, 'format' => 'array']);
			$followers = Twitter::getFollowers(['user_id' => $id, 'count' => 10, 'format' => 'array']);
			return view('home', compact('tweets', 'followers'));
			// dd($followers);
		} 
		else {
			return redirect('welcome');
		}*/
		//return redirect('home');
		//return $user->getName();
		//dd($user);


		$id = $user->getId();
		 	$tweets = Twitter::getUserTimeline(['user_id' => $id, 'count' => 10, 'format' => 'array']);
		dd($tweets);
		//return $user->getName();
		
	}

}
