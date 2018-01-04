<?php

use App\Task;
use Illuminate\Http\Request;
use App\Http\Resources\Task as TaskResource;
use App\Events\CurdEvent;

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
Route::group(['middleware' => ['api']], function () {
    Route::post('auth/register', 'Auth\RegisterController@create');
    Route::post('auth/login', 'Auth\ApiAuthController@login');
});

Route::middleware('jwt.auth')->get('/tasks', function (Request $request) {
    return new TaskResource(Task::all());
});

Route::middleware('jwt.auth')->get('/remaining_tasks', function (Request $request) {
    return new TaskResource(Task::where('done', false)->get());
});

Route::middleware('jwt.auth')->get('/complete_tasks', function (Request $request) {
    return new TaskResource(Task::where('done', true)->get());
});

Route::middleware('jwt.auth')->post('/create_task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return $validator->errors()->toJson();
    }

    $task = new Task;
    $task->name = $request->name;
    event(new CurdEvent($task, 'create'));
    $task->save();
    return new TaskResource($task);
});


Route::middleware('jwt.auth')->post('/edit_task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
        'id'  => 'required|max:255'
    ]);

    if ($validator->fails()) {
        return $validator->errors()->toJson();
    }

    $task = Task::find($request->id);
    if(!$task){
      echo json_encode(array('error' => 'task not found'));
      return;
    }

    $task->name = $request->name;
    event(new CurdEvent($task, 'edit'));
    $task->save();
    return new TaskResource($task);
});

Route::middleware('jwt.auth')->post('/complete_task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'id' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return $validator->errors()->toJson();
    }

    $task = Task::find($request->id);

    if(!$task){
      echo json_encode(array('error' => 'task not found'));
      return;
    }

    $task->done = true;
    event(new CurdEvent($task, 'complete'));
    $task->save();
    return new TaskResource($task);
});

Route::middleware('jwt.auth')->post('/delete_task', function (Request $request) {

    $task = Task::find($request->id);

    if(!$task){
      echo json_encode(array('error' => 'task not found'));
      return;
    }

    Task::findOrFail($request->id)->delete();
    event(new CurdEvent($task, 'delete'));
    return new TaskResource($task);
});
