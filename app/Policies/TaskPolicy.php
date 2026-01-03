<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    // Manager can create task
    public function create(User $user) :bool
    {
        return $user->role === 'manager';
    }

    // Manager can update task status
    public function update(User $user, Task $task) :bool
    {
        return $user->role === 'manager';
    }

    public function view(User $user, Task $task) :bool
    {
        if($user->role === 'manager'){
            return true;
        }

        return $task->assigneed_id === $user->id;
    }

    public function updateStatus(User $user, Task $task) :bool
    {
        return $task->assignee_id === $user->id;
    }

}