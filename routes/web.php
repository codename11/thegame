<?php

use Illuminate\Support\Facades\Route;

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
//Premestiti u api folder i sve sto uz to ide.
//Route::post('/createGame', "GamesController@createGame");
//Route::post('/addArmy', "GamesController@addArmy");
//Route::get('/attackStrategy', "GamesController@attackStrategy");
//Route::get('/listGames', "GamesController@listGames");

//Route::get('/setupScreen', "GamesController@setupScreen");
//Route::get('/battlefield', "GamesController@battlefield");

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

/*Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');*/
