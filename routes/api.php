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

Route::middleware('auth:api')->get('/tasks', function (Request $request) {
    return new TaskResource(Task::all());
});

Route::middleware('auth:api')->get('/remaining_tasks', function (Request $request) {
    return new TaskResource(Task::where('done', false)->get());
});

Route::middleware('auth:api')->get('/complete_tasks', function (Request $request) {
    return new TaskResource(Task::where('done', true)->get());
});

Route::middleware('auth:api')->post('/create_task', function (Request $request) {
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


Route::middleware('auth:api')->post('/edit_task', function (Request $request) {
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

Route::middleware('auth:api')->post('/complete_task', function (Request $request) {
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

Route::middleware('auth:api')->post('/delete_task', function (Request $request) {

    $task = Task::find($request->id);

    if(!$task){
      echo json_encode(array('error' => 'task not found'));
      return;
    }

    Task::findOrFail($request->id)->delete();
    event(new CurdEvent($task, 'delete'));
    return new TaskResource($task);
});
