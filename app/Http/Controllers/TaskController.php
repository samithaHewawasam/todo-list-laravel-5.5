<?php

namespace App\Http\Controllers;

use Validator;
use App\Task;
use App\UsersTasks;
use Illuminate\Http\Request;
use App\Events\CurdEvent;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function tasklist()
    {
      $id = \Auth::user()->id;
      $tasks = DB::table('tasks')
                  ->join('users_tasks', 'tasks.id', '=', 'users_tasks.task_id')
                  ->join('users', 'users.id', '=', 'users_tasks.user_id')
                  ->where('users.id', '=', $id)
                  ->select('tasks.*')
                  ->get();

      $users = DB::table('users')->get();

      return view('tasks',[
          'tasks' => $tasks,
          'users' => $users
      ]);
    }

    public function taskcreate(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'name' => 'required|max:255',
      ]);

      if ($validator->fails()) {
          return redirect('/tasklist')
              ->withInput()
              ->withErrors($validator);
      }
      $task = new Task;
      $id = \Auth::user()->id;



      $task->name = $request->name;
      $task->save();

      $users_tasks = new UsersTasks;
      $users_tasks->user_id = $id;
      $users_tasks->task_id = $task->id;
      $users_tasks->save();

      event(new CurdEvent($task, 'create'));
      return redirect('/tasklist');
    }

    public function deletetask($id)
    {
      $task = Task::find($id);
      Task::findOrFail($id)->delete();
      event(new CurdEvent($task, 'delete'));
      return redirect('/tasklist');
    }

    public function gettask(Request $request)
    {
      $task = Task::find($request->id);
      return view('edit', ['task' => $task ]);
    }

    public function taskedit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/tasklist/'.$request->id)
                ->withInput()
                ->withErrors($validator);
        }

        $task = Task::find($request->id);
        $task->name = $request->name;
        event(new CurdEvent($task, 'edit'));
        $task->save();
        return redirect('/tasklist');
    }

    public function taskcomplete(Request $request)
    {

      $task = Task::find($request->id);
      $task->done = true;
      event(new CurdEvent($task, 'complete'));
      $task->save();
      return redirect('/tasklist');
    }

    public function taskassign(Request $request)
    {

      $users_tasks = UsersTasks::where('task_id', $request->id)
                ->first();
      $users_tasks->user_id = $request->user_id;

      $task = Task::find($request->id);

      event(new CurdEvent($task, 'assign'));
      $users_tasks->save();
      return redirect('/tasklist');
    }


}
