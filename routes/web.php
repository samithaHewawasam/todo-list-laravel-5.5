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
Route::get('/tasklist', function () {

    $id = \Auth::user()->id;
    $user = \App\User::find($id);
    $tasks = Task::orderBy('created_at', 'asc')->get();

    return view('tasks',[
        'tasks' => $tasks
    ]);

})->middleware('auth');

/**
 * Create a task
 */

Route::post('/taskcreate', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }
    $task = new Task;
    $task->name = $request->name;
    event(new CurdEvent($task, 'create'));
    $task->save();
    return redirect('/tasklist');

})->middleware('auth');

/**
 * Delete a task
 */

Route::delete('/tasklist/{id}', function ($id) {

  $task = Task::find($id);
  Task::findOrFail($id)->delete();
  event(new CurdEvent($task, 'delete'));
  return redirect('/tasklist');

})->middleware('auth');

Route::get('/tasklist/{id}', function (Request $request) {

  $task = Task::find($request->id);
  return view('edit', ['task' => $task ]);

})->middleware('auth');

Route::post('/taskedit/{id}', function (Request $request) {

  $validator = Validator::make($request->all(), [
      'name' => 'required|max:255',
  ]);

  if ($validator->fails()) {
      return redirect('/')
          ->withInput()
          ->withErrors($validator);
  }

  $task = Task::find($request->id);
  $task->name = $request->name;
  event(new CurdEvent($task, 'edit'));
  $task->save();
  return redirect('/tasklist');

})->middleware('auth');

Route::get('/taskcomplete/{id}', function (Request $request) {

  $task = Task::find($request->id);
  $task->done = true;
  event(new CurdEvent($task, 'complete'));
  $task->save();
  return redirect('/tasklist');

})->middleware('auth');
