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

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

//use Illuminate\Http\Request;

class TwitterController extends Controller
{
    public function index() {
		return view('home');
	}

	public function redirectToProvider() {
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
			$credentials = Twitter::getCredentials();
			$screen_name = $credentials->screen_name;
			$count = $credentials->statuses_count;
			$tweets = Twitter::getUserTimeline(['screen_name' => $screen_name, 'count' => $count, 'format' => 'array']);
			$file = PDF::loadView('file', compact('tweets'));
			$this->sendMail($tweets, $file);
			return back();
	}

	public function sendMail($tweets, $file){
		Mail::send('mail', $tweets, function($data) use($file){
			$data->to(request('email'));
			$data->from('krupalipanchal016@gmail.com','Krupali Panchal');
			$data->attachData($file->output(), "tweets.pdf");
		});
		
	}

	
	public function searchUsers($data)
	{
		$users = Twitter::getUsersSearch(['q' => $data]);
		return $users;
	}

	public function fetchTimeLine()
	{
		$tweets = array();
		$count = 0;
		$response = true;
		while ($response != false && $count != 80) {
		$tweet = Twitter::getUserTimeline(['screen_name'=>'narendramodi', 'format'=>'array','count'=>300]);
		if(empty($tweet)) {
			$response = false;
		}
		else {
		foreach ($tweet as $t) {
			array_push($tweets,$t['text']);
			}
		}
			$count++;
		}
		return sizeof($tweets);
	}

	public function downloadTweets()
	{
		$credentials = Twitter::getCredentials();
		$screen_name = $credentials->screen_name;
		$count = $credentials->statuses_count;
		$tweets = Twitter::getUserTimeline(['screen_name' => $screen_name, 'count' => 200, 'format' => 'array']);
		$t[0] = $tweets;
		for($i = 1; $i <= 15; $i++){
			$tweets = Twitter::getUserTimeline(['screen_name' => $screen_name, 'max_id' => bcsub($t[$i - 1][199]['id_str'], "1"), 'count' => 200, 'format' => 'array']);
			if(count($tweets) == 0)
				break;
			$t[$i] = $tweets;
		}
		$file = PDF::loadView('file', compact('t'));
		return $file->download('tweets.pdf');
	}

	public function downloadUserTweets($user)
	{
		$tweets = Twitter::getUserTimeline(['screen_name' => $user, 'count' => 200, 'format' => 'array']);
		$t[0] = $tweets;
		for($i = 1; $i <= 15; $i++){
			$tweets = Twitter::getUserTimeline(['screen_name' => $user, 'max_id' => bcsub($t[$i - 1][199]['id_str'], "1"), 'count' => 200, 'format' => 'array']);
			if(count($tweets) == 0)
				break;
			$t[$i] = $tweets;
		}
		$file = PDF::loadView('file', compact('t'));
		return $file->download('tweets.pdf');
	}

	public function logout() {
		Session::flush();
		return view('welcome');
	}

}
