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
Route::post('/login', 'api\AuthController@login');
//Ubaciti passport.
Route::post('/createGame', "api\GamesController@createGame");
Route::post('/addArmy', "api\GamesController@addArmy");
Route::get('/listGames', "api\GamesController@listGames");
Route::get('/listArmies', "api\GamesController@listArmies");
Route::post('/attackStrategy', "api\GamesController@attackStrategy");

//Views
Route::get('/setupScreen', "api\GamesController@setupScreen");
Route::get('/battlefield', "api\GamesController@battlefield");

//Commencing battle
Route::post('/commenceBattle', "api\GamesController@commenceBattle");
