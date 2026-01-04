<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use DomainException;

class TaskService
{
    /**
     * Mark a task as completed.
     *
     * @throws DomainException
     */
    public function complete(Task $task): void
    {
        if ($task->status === TaskStatus::Completed) {
            return;
        }

        if ($this->hasIncompleteDependencies($task)) {
            throw new DomainException(
                'Task cannot be completed until all dependencies are completed.'
            );
        }

        $task->update([
            'status' => TaskStatus::Completed,
        ]);
    }

    /**
     * Check if a task has any incomplete dependencies.
     */
    public function hasIncompleteDependencies(Task $task): bool
    {
        return $task->dependencies()
            ->where('status', '!=', TaskStatus::Completed->value)
            ->exists();
    }

    /**
     * Assign dependencies to a task.
     *
     * @throws DomainException
     */
    public function assignDependencies(Task $task, array $dependencyIds): void
    {
        if (in_array($task->id, $dependencyIds, true)) {
            throw new DomainException('A task cannot depend on itself.');
        }

        $task->dependencies()->syncWithoutDetaching($dependencyIds);
    }
}
