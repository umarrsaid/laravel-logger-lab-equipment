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

// Route::get('/', function () {
//     return view('');
// });

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');
Route::get('/register','Auth\RegisterController@registerPage')->name('register');
Route::get('/data-log','ActivityController@pagesDataLog');
Route::get('/table/activity/data-log/{id_user?}/{tgl_awal?}/{tgl_akhir?}/{satuan?}/{kolam?}','ActivityController@tableDataLog');

Route::middleware('auth')->group(function () {
    //filter
    Route::get('/logger/filter/{id?}/{id_user?}/{tgl_awl?}/{waktu_aw?}/{tgl_akh?}/{waktu_ak?}','ApiController@filterLogger');

    //report logger excel
    Route::get('/logger/report-data','ActivityController@reportLogger');
    Route::get('/logger/cetak/{id_user?}/{tgl_awal?}/{tgl_akhir?}/{satuan?}/{kolam?}','ReportController@cetakData');

    //delete data activity logger
    Route::post('/logger/delete','ActivityController@deleteDataLogging');

    //get data kolam berdasarkan satuan
    Route::get('/logger/data-kolam','ActivityController@getDataKolam');

    //modal
    Route::get('/modal-dm','DataMasterController@modal');

    //machine
    Route::get('/machine','DataMasterController@pagesMachine');
    Route::get('/table/data-master/machine','DataMasterController@tableMachine');
    Route::match(['get','post','put','delete'], '/crud/machine/{id?}', 'DataMasterController@crudMachine');
    //end

    //measure
    Route::get('/measure','DataMasterController@pagesMeasure');
    Route::get('/table/data-master/measure','DataMasterController@tableMeasure');
    Route::match(['get','post','put','delete'], '/crud/measure/{id?}', 'DataMasterController@crudMeasure');
    //end

    //logger
    Route::get('/logger','DataMasterController@pagesLogger');
    Route::get('/table/data-master/logger','DataMasterController@tableLogger');
    Route::match(['get','post','put','delete'], '/crud/logger/{id?}', 'DataMasterController@crudLogger');
    //end

    //Pool
    Route::get('/location','DataMasterController@pagesPool');
    Route::get('/table/data-master/pool','DataMasterController@tablePool');
    Route::match(['get','post','put','delete'], '/crud/pool/{id?}', 'DataMasterController@crudPool');
    //end

    //User
    Route::get('/user','DataMasterController@pagesUser');
    Route::get('/table/data-master/user','DataMasterController@tableUser');
    Route::match(['get','post','put','delete'], '/crud/user/{id?}', 'DataMasterController@crudUser');
    //end
});
