<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::query()
            ->with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return $this->jsonResponse(data: $tasks, message: 'Tasks retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $taskData = $request->validated();
        $taskData['user_id'] = Auth::id();
        
        $task = Task::query()->create($taskData);

        return $this->jsonResponse(data: $task, message: 'Task created successfully.', responseCode: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->jsonResponse(data: $task->load('user'), message: 'Task retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return $this->jsonResponse(data: $task->load('user'), message: 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return $this->jsonResponse(message: 'Task deleted successfully.', responseCode: 200);
    }
}
