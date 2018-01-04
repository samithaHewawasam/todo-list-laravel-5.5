<?php

namespace App\Http\Controllers;

use Validator;
use App\Task;
use Illuminate\Http\Request;
use App\Events\CurdEvent;

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
      $user = \App\User::find($id);
      $tasks = Task::orderBy('created_at', 'asc')->get();

      return view('tasks',[
          'tasks' => $tasks
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
      $task->name = $request->name;
      event(new CurdEvent($task, 'create'));
      $task->save();
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
}
