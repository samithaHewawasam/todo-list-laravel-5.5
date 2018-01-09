<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{

  public function list(int $user_id)
  {

      $tasks = DB::table('tasks')
                  ->join('users_tasks', 'tasks.id', '=', 'users_tasks.task_id')
                  ->join('users', 'users.id', '=', 'users_tasks.user_id')
                  ->where('users.id', '=', $user_id)
                  ->select('tasks.*')
                  ->get();

      $users = DB::table('users')->get();

      return [
          'tasks' => $tasks,
          'users' => $users
      ];

  }

  public function create(int $user_id, String $name, $dueDate)
  {

    $this->name = $name;
    $this->dueDate = $dueDate;
    $this->save();

    $users_tasks = new UsersTasks;
    $users_tasks->user_id = $user_id;
    $users_tasks->task_id = $this->id;

    return $users_tasks->save();
  }

}
