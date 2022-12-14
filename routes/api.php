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


Route::get('/logger/filter/{id?}/{id_user?}/{tgl?}/{waktu_aw?}/{waktu_ak?}','ApiController@filterLogger');

Route::get('/login','ApiController@loginLogger');
Route::post('/post-alat','ApiController@postDataAlat');

Route::get('/chart','ApiController@chartPengukuran')->name('chart');

Route::get('/data-kolam','ApiController@dataKolam');