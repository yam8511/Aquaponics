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

# 會員系統的route
Auth::routes();

/**
 * 前台Route
 */
Route::get('/', 'PlantController@index');
Route::get('plant_library', 'PlantController@library');
Route::get('my_plant', 'PlantController@myPlant');
Route::get('show_data', 'PlantController@showData');
Route::get('share', 'PlantController@share');
Route::get('share/{plant}', 'PlantController@share_user');
Route::get('share/{plant}/{user}', 'PlantController@showData');
Route::get('contact', 'PlantController@contact');
Route::post('contact', 'PlantController@contact');

/**
 * 後端Route
 */
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::get('environment', ['as' => 'environment', 'uses' => 'DashboardController@environment']);
    Route::post('environment', ['as' => 'setEnvironment', 'uses' => 'DashboardController@setEnvironment']);
});

/**
 * 使用者管理
 */
Route::post('user/check', ['as' => 'user.check', 'uses' => 'UserController@checkEmail']);
Route::resource('user', 'UserController');

/**
 * Upload Files
 */
Route::get('upload', 'UploadController@index');
Route::get('picture', 'UploadController@picture');
//Route::get('addEnv', 'UploadController@addEnv');
//Route::post('addEnv', 'UploadController@storeEnv');
Route::get('env/{all?}', 'UploadController@env');
Route::get('seed/{all?}', 'UploadController@seed');
Route::get('farm/{farm}', 'UploadController@editFarm');
Route::post('farm/{farm}', 'UploadController@saveFarm');

/**
 * 後端 Ajax Route
 */
Route::post('/getEnvironment', 'DashboardController@getEnvironment');
Route::post('/changeStatus', 'DashboardController@changeStatus');

/**
 * 前台 Ajax Route
 */
Route::post('addPlant', 'DataController@addPlant');
Route::post('addOtherPlant', 'DataController@addOtherPlant');
Route::post('endPlant', 'DataController@endPlant');
Route::get('getEnvData', 'DataController@getEnvData');
