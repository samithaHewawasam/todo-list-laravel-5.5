<?php

namespace App\Http\Controllers;

use Validator;
use App\Task;
use App\UsersTasks;
use Illuminate\Http\Request;
use App\Events\CurdEvent;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use JWTAuthException;

class ApiTaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $token = JWTAuth::setToken($request->token);
        $this->user = JWTAuth::toUser($token);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function tasklist(Request $request)
    {
      $task = new Task;

      return response()->json([
          'response' => 'success',
          'result' => $task->list($this->user->id)
      ]);
    }

    public function taskcreate(Request $request)
    {

      $validator = Validator::make($request->all(), [
          'name' => 'required|max:255',
      ]);

      if ($validator->fails()) {
        return response()->json(['errors'=>$validator->errors()]);
      }

      $task = new Task;
      event(new CurdEvent($task, 'create'));

      return response()->json([
          'response' => 'success',
          'result' => $task->create($this->user->id, $request->name, $request->dueDate)
      ]);
    }

    public function deletetask(Request $request)
    {
      $task = Task::find($request->id);

      if($task)
      event(new CurdEvent($task, 'delete'));

      return response()->json([
          'response' => 'success',
          'result' => Task::findOrFail($request->id)->delete()
      ]);
    }

    public function gettask(Request $request)
    {
      $task = Task::find($request->id);
      return response()->json([
          'response' => 'success',
          'result' => ['task' => $task ]
      ]);
    }

    public function taskedit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()]);
        }

        $task = Task::find($request->id);
        $task->name = $request->name;
        event(new CurdEvent($task, 'edit'));

        return response()->json([
            'response' => 'success',
            'result' => $task->save()
        ]);
    }

    public function taskcomplete(Request $request)
    {

      $task = Task::find($request->id);
      $task->done = true;
      event(new CurdEvent($task, 'complete'));
      $task->save();
      return response()->json([
          'response' => 'success',
          'result' => $task->save()
      ]);
    }

    public function taskassign(Request $request)
    {

      $users_tasks = UsersTasks::where('task_id', $request->id)
                ->first();
      $users_tasks->user_id = $request->user_id;

      $task = Task::find($request->id);

      event(new CurdEvent($task, 'assign'));

      return response()->json([
          'response' => 'success',
          'result' => $users_tasks->save()
      ]);
    }


}
