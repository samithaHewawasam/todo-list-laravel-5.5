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

use App\Task;
use Illuminate\Http\Request;
use App\Events\CurdEvent;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Display All Tasks
 */
Route::get('/tasklist', 'TaskController@tasklist')->middleware('auth');

/**
 * Create a task
 */

Route::post('/taskcreate', 'TaskController@taskcreate')->middleware('auth');

/**
 * Delete a task
 */

Route::delete('/tasklist/{id}', 'TaskController@deletetask')->middleware('auth');

Route::get('/tasklist/{id}', 'TaskController@gettask')->middleware('auth');

Route::post('/taskedit/{id}', 'TaskController@taskedit')->middleware('auth');

Route::get('/taskcomplete/{id}', 'TaskController@taskcomplete')->middleware('auth');
