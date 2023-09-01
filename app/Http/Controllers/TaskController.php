<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\task;
use Illuminate\Support\Carbon;
class TaskController extends Controller
{
    public function index()
    {
        return task::orderBy('created_at', 'DESC')->get();
    }


    public function store(Request $request)
    {
        $request->all();
        $newTask = new task;
        $newTask["title"] = $request["title"];
        $newTask->save();
        return $newTask;
    }

    public function update(Request $request, $id)
    {
        $task = task::find($id);
        if($task){
            $task['finished_at'] = Carbon::now();
            $task['finished'] = !$task['finished'];
            $task->save();
            return $task;
        }

        return "Task not found";
    }

    public function destroy($id)
    {
        $task = task::find($id);
        if($task){
            $task->delete();
            return "Task deleted";
        }
        return "Task not found";
    }
}
