<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/search', function () {
    return view('search');
});

Route::get('home', array('as' => 'home', 'uses' => function(){
  return view('home');
}));

Route::get('/auth/twitter', 'TwitterController@redirectToProvider');
Route::get('/auth/twitter/callback', 'TwitterController@handleProviderCallback');
Route::get('/twitter/{screen_name}', 'TwitterController@getFollowersTweets');
Route::get('/home', 'TwitterController@getTweetsAndFollowers');
Route::post('/generate_pdf', 'TwitterController@generatePDF');
Route::get('/send', 'TwitterController@sendMail');
Route::get('/mail', 'TwitterController@mail');
Route::get('/download', 'TwitterController@downloadTweets');
Route::get('/download_user_tweets/{user}', 'TwitterController@downloadUserTweets');
Route::get('/search_users/{data}', 'TwitterController@searchUsers');
Route::get('/logout', 'TwitterController@logout');