<?php

use Illuminate\Http\Request;

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

Route::post('register', 'Api\ApiRegisterController@register');
Route::post('login', 'Api\ApiLoginController@login');
Route::get('roles', 'Api\ApiRegisterController@getRoles');
Route::get('parties/{date?}', 'Api\ApiPartyController@getParties');

Route::middleware(['jwt.auth'])->group(function () {

    Route::post('logout', 'Api\ApiLoginController@logout');
    Route::get(
        'songs',
        ['uses' => 'Api\ApiSongController@getSongs', 'middleware' => 'roles', 'roles' => ['guest', 'admin', 'dj']]
    );
    Route::delete(
        'songs/{id}',
        ['uses' => 'Api\ApiSongController@deleteSong', 'middleware' => 'roles', 'roles' => ['admin', 'dj']]
    );
    Route::post(
        'songs',
        ['uses' => 'Api\ApiSongController@saveSong', 'middleware' => 'roles', 'roles' => ['admin', 'dj']]
    );
    Route::put(
        'songs/{id}',
        ['uses' => 'Api\ApiSongController@updateSong', 'middleware' => 'roles', 'roles' => ['admin', 'dj']]
    );
    Route::post(
        'parties',
        ['uses' => 'Api\ApiPartyController@saveParty', 'middleware' => 'roles', 'roles' => ['admin', 'party_maker']]
    );
    Route::post(
        'parties/{id}',
        ['uses' => 'Api\ApiPartyController@updateParty', 'middleware' => 'roles', 'roles' => ['admin', 'party_maker']]
    );
    Route::delete(
        'parties/{id}',
        ['uses' => 'Api\ApiPartyController@deleteParty', 'middleware' => 'roles', 'roles' => ['admin', 'party_maker']]
    );
    Route::post(
        'parties/join/{id}',
        ['uses' => 'Api\ApiPartyController@joinParty', 'middleware' => 'roles', 'roles' => ['guest']]
    );
    Route::get(
        'parties/start/{id}',
        ['uses' => 'Api\ApiPartyController@startParty', 'middleware' => 'roles', 'roles' => ['party_maker']]
    );
});
