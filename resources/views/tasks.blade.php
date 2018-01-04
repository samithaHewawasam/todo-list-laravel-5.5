@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New Task
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Task Form -->
                    <form action="{{ url('taskcreate')}}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-3 control-label">Task</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control" value="{{ old('task') }}">
                            </div>
                        </div>

                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Tasks -->
            @if (count($tasks) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Current Tasks
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Task</th>
                                <th>Delete</th>
                                <th>Edit</th>
                                <th>Complete</th>
                                <th>Assign User</th>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="table-text"><div>{{ $task->name }}</div></td>

                                        <!-- Task Delete Button -->
                                        <td>
                                            <form action="{{ url('tasklist/'.$task->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}

                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-btn fa-trash"></i>Delete
                                                </button>
                                            </form>

                                        </td>
                                        <td>
                                          <form action="{{ url('tasklist/'.$task->id) }}" method="GET">
                                              {{ csrf_field() }}
                                              {{ method_field('EDIT') }}

                                              <button type="submit" class="btn btn-primary">
                                                  <i class="fa fa-btn fa-edit"></i>Edit
                                              </button>
                                          </form>
                                        </td>
                                        <td>
                                          @if (!$task->done)
                                          <form action="{{ url('taskcomplete/'.$task->id) }}" method="GET">
                                              {{ csrf_field() }}
                                              {{ method_field('COMPLETE') }}
                                              <button type="submit" class="btn btn-success">
                                                  <i class="fa fa-btn fa-flag"></i>Done
                                              </button>
                                          </form>
                                          @elseif ($task->done)
                                          <i class="fa fa-btn fa-flag">Done</i>
                                          @endif
                                        </td>
                                        <td>
                                        <form action="{{ url('taskassign/'.$task->id) }}" method="GET">
                                          <select name="user_id">
                                            @foreach ($users as $user)
                                              <option value="{{$user->id}}">{{ $user->name }}</option>
                                            @endforeach
                                          </select>
                                          <button type="submit" class="btn btn-success">
                                              <i class="fa fa-btn fa-flag"></i>Assign
                                          </button>
                                        </form>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
