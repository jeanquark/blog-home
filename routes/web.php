<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::group(['middleware' => ['web']], function () {
    Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));
    Route::get('about', array('as' => 'about', 'uses' => 'HomeController@about'));
	Route::get('services', array('as' => 'services', 'uses' => 'HomeController@services'));
	Route::get('contact', array('as' => 'contact', 'uses' => 'HomeController@contact'));
	Route::post('contact', array('as' => 'process_contact', 'uses' => 'HomeController@process_contact'));
	Route::get('post/{slug}', array('as' => 'post', 'uses' => 'BlogController@showPost'));
	Route::post('post/{slug}/comment', array('as' => 'post.comment', 'uses' => 'BlogController@comment'));
	Route::post('post/{id}/reply', array('as' => 'post.reply', 'uses' => 'BlogController@reply'));
	Route::get('tag/{slug}', array('as' => 'showTaggedPost', 'uses' => 'BlogController@showTaggedPost'));
	Route::get('user/{hash}', array('as' => 'showUserPost', 'uses' => 'BlogController@showUserPost'));
	Route::get('search/{slug}', array('as' => 'showSearchedPost', 'uses' => 'BlogController@showSearchedPost'));
	Route::get('search', array('as' => 'search', 'uses' => 'BlogController@search'));
});

Route::group(['middleware' => ['web', 'guest']], function() {
	Route::get('login', array('as' => 'login', 'uses' => 'SessionController@create'));
	Route::post('login', array('as' => 'session.store', 'uses' => 'SessionController@store'));
});

Route::group(['middleware' => 'user'], function() {
	Route::get('logout', array('as' => 'logout', 'uses' => 'SessionController@logout'));
});

Route::group(['middleware' => 'mod', 'as' => 'admin.'], function () {
	Route::get('admin', array('as' => 'index', 'uses' => 'AdminController@index'));
	Route::resource('admin/post', 'PostController');
	Route::resource('admin/tag', 'TagController');
	Route::resource('admin/comment', 'CommentController');
	Route::resource('admin/reply', 'ReplyController');
});

Route::group(['middleware' => 'admin', 'as' => 'admin.'], function () {
	Route::resource('admin/user', 'UserController');
	Route::post('admin/user/{user}/password', array('as' => 'user.password', 'uses' => 'UserController@changePassword'));
	Route::post('admin/user/{user}/role', array('as' => 'user.role', 'uses' => 'UserController@updateRole'));
	Route::post('admin/user/{user}/activation', array('as' => 'user.activation', 'uses' => 'UserController@updateActivation'));
});



Route::group(['middleware' => ['admin'], 'as' => 'admin.'], function(){
	Route::resource('books', 'BooksController');
});