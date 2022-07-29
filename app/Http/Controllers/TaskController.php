<?php

namespace App\Http\Controllers;

use App\Models\Task;
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


        return 'Create Task';
    }
}
