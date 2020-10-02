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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Actual routes.
Route::post('/register', 'api\AuthController@register');
Route::get('/login', 'api\AuthController@login')->name("login");
//Ubaciti passport.
Route::post('/createGame', "api\GamesController@createGame")->middleware('auth:api');
Route::post('/addArmy', "api\GamesController@addArmy")->middleware('auth:api');
Route::get('/listGames', "api\GamesController@listGames")->middleware('auth:api');
Route::get('/listArmies', "api\GamesController@listArmies")->middleware('auth:api');
Route::post('/attackStrategy', "api\GamesController@attackStrategy")->middleware('auth:api');

//Views
Route::get('/setupScreen', "api\GamesController@setupScreen");
Route::get('/battlefield', "api\GamesController@battlefield");

//Commencing battle
Route::post('/commenceBattle', "api\GamesController@commenceBattle")->middleware('auth:api');