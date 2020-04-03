<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//tambahkan ini
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login'); //do login



Route::middleware(['jwt.verify'])->group(function(){
  Route::get('/daily', 'DailyController@index');
  Route::get('daily/{limit}/{offset}', "DailyController@getAll"); //read poin
  Route::post('/daily', 'DailyController@store');
  Route::delete('/daily/{id}', 'DailyController@delete');

  Route::get('login/check', "UserController@LoginCheck"); //cek token
	Route::post('logout', "UserController@logout"); //cek token
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
