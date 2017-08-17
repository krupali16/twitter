<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App;
use Twitter;
use Socialite;
use Session;
use Mail;
use PDF;
use Redirect;
use SendMail;
use Illuminate\Support\Facades\Request;
//use Illuminate\Http\Request;

class TwitterController extends Controller
{
    public function index() {
		return view('home');
	}

	public function redirectToProvider() {
		// return Socialite::driver('twitter')->redirect();
		// dd($temp);
		// $login_with_twitter = true;
		// $force_login = false;
		// $url = url('/') . '/auth/login/callback';
		// Twitter::reconfig(['token'=> '', 'secret'=> '']);
		// $token = Twitter::getRequestToken($url);
		// // dd($token);
		// if(isset($token['oauth_token_secret'])) {
		// 	$url = Twitter::getAuthorizeURL($token, $login_with_twitter, $force_login);
		// 	dd($url);
		// }
		if (Session::has('access_token')) {
			return $this->getTweetsAndFollowers();
		}
		else
		{
			$sign_in_twitter = true;
			$force_login = false;
			
			Twitter::reconfig(['token' => '', 'secret' => '']);
			$token = Twitter::getRequestToken(url('/').'/auth/twitter/callback');

			if (isset($token['oauth_token_secret']))
			{
				$url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

				Session::put('oauth_state', 'start');
				Session::put('oauth_request_token', $token['oauth_token']);
				Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

				return redirect($url);
			}
		}

	}

	public function handleProviderCallback() {

		/*$user = Socialite::driver('twitter')->user();

		  $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser);
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


		// $id = $user->getId();

		// $tweets = Twitter::getUserTimeline(['user_id' => $id, 'count' => 10, 'format' => 'array']);
		// $followers = Twitter::getFollowers(['user_id' => $id, 'count' => 25, 'format' => 'array']);
		
		//return view('/home', compact('tweets', 'followers'));
		//return view('/home');


		if (Session::has('oauth_request_token'))
	{
		$request_token = [
			'token'  => Session::get('oauth_request_token'),
			'secret' => Session::get('oauth_request_token_secret'),
		];

		Twitter::reconfig($request_token);

		$oauth_verifier = false;

		if (Request::has('oauth_verifier'))
		{
			$oauth_verifier = Request::get('oauth_verifier');
			$token = Twitter::getAccessToken($oauth_verifier);
		}

		if (!isset($token['oauth_token_secret']))
		{
			return redirect::to('welcome');
		}

		$credentials = Twitter::getCredentials();

		if (is_object($credentials) && !isset($credentials->error))
		{			
			Session::put('access_token', $token);

			return $this->getTweetsAndFollowers();
		}

		return redirect::to('welcome');
	}
		
	}	

	public function getTweetsAndFollowers()
	{
		$token = Session::get('access_token');
		$screen_name = Twitter::getCredentials()->screen_name;

		$followers = Twitter::getFollowers(['screen_name' => $screen_name, 'format' => 'array']);
		$tweets = Twitter::getUserTimeline(['screen_name' => $screen_name,  'count' => 10, 'format' => 'array']);
		return view('home', compact('tweets', 'followers'));
	}

	public function getFollowersTweets($screen_name)
	{		
		$tweets = Twitter::getUserTimeline(['screen_name' => $screen_name, 'count' => 25, 'format' => 'json']);
		$tweet = json_decode($tweets);

		$arr = array();
		foreach($tweet as $data)
		{
			$arr[] = $data->text;
		}

		return $arr;
	}

	public function generatePDF(){

			//$mail_id = request('email');
			$credentials = Twitter::getCredentials();
			$screen_name = $credentials->screen_name;
			$count = $credentials->statuses_count;
			$tweets = Twitter::getUserTimeline(['screen_name' => $screen_name, 'count' => $count, 'format' => 'array']);
			$file = PDF::loadView('file', compact('tweets'));
			//$this->sendMail($tweets, $file);
	}

	public function sendMail($tweets, $file){
		// Mail::send('mail', $tweets, function($data) use($file){
		// 	$data->to('krupalipanchal1995@gmail.com');
		// 	$data->from('krupalipanchal1995@gmail.com','Krupali Panchal');
		// 	$data->attachData($file->output(), "tweets.pdf");
		// });
		// return;
		Mail::send(new SendMail());
	}
	public function mail()
	{
		Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message) {
		$message->to('krupalipanchal1995@gmail.com');
	});
	}
}
