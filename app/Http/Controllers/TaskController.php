<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Requests\AssignDependenciesRequest;

class TaskController extends Controller
{
     /**
     * List tasks.
     * Managers see all tasks.
     * Users see only their assigned tasks.
     */
    public function index(Request $request)
    {
        $query = Task::query();


        if($request->user()->role !== 'manager')
        {
            $query->assignedTo($request->user()->id);
        }

        if($request->filled('status'))
        {
            $query->where('status', $request->string('status'));
        }

        if($request->filled(['from','to']))
        {
            $query->dueBetween($request->date('from'), $request->date('to'));
        }
        return response()->json($query->with(['assignedUser', 'creator'])->get(), 200);
    }

    // Create a new task (manager only)
    public function store(StoreTaskRequest $request, TaskService $taskService)
    {
        $this->authorize('create', Task::class);

        $data = $request->validated();

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'assignee_id' => $data['assignee_id'],
            'due_date' => $data['due_date'] ?? null,
            'created_by' => $request->user()->id,
            'status' => TaskStatus::Pending
        ]);

        if (!empty($data['dependencies'])) {
            $taskService->assignDependencies($task, $data['dependencies']);
        }

        return response()->json($task->load('dependencies'), 201);
    }

     // Update task details (Manager only).

    public function update(StoreTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return response()->json($task, 200);
    }


     // Update task status (Assigned user only).

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task, TaskService $taskService) {
        $this->authorize('updateStatus', $task);

        if ($request->input('status') === TaskStatus::Completed->value) {
            $taskService->complete($task);
        } else {
            $task->update(['status' => $request->input('status')]);
        }

        return response()->json(['message' => 'Status updated successfully.'], 200);
    }


     // Assign dependencies to a task (Manager only).

    public function assignDependencies(AssignDependenciesRequest $request, Task $task, TaskService $taskService
    ) {
        $this->authorize('update', $task);

        $taskService->assignDependencies($task, $request->validated()['dependencies']);

        return response()->json(['message' => 'Dependencies assigned successfully.'], 200);
    }
}
