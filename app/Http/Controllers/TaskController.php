<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        try {
            $title = $request->input('title');
            $userId = auth()->user()->id;

            $newTask = new Task();
            $newTask->title = $title;
            $newTask->user_id = $userId;
            $newTask->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Task created successfully"
                ]
            );
        } catch (\Exception $exception) {
            Log::error('Error creating task: '.$exception->getMessage());

            return response()->json(
                [
                    "success" => true,
                    "message" => "Error creating task"
                ],
                500
                ); 
        }
    }

    public function getAllTasksByUserId()
    {
        try {
            $userId = auth()->user()->id;

            $tasks = User::find($userId)->tasks;

            // $tasks = Task::query()->where('user_id', '=', $userId)->get()->toArray();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Tasks retrieved successfully",
                    "data" => $tasks
                ]
            );
           
        } catch (\Exception $exception) {
            Log::error('Error getting all tasks: '.$exception->getMessage());

            return response()->json(
                [
                    "success" => true,
                    "message" => "Error getting task"
                ],
                500
                ); 
        }

        return 'GetAllTasks';
    }
}
