<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assignee_id',
        'created_by',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'due_date' => 'date',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dependencies()
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'task_id',
            'depends_on_task_id'
        );
    }

    public function dependents()
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'depends_on_task_id',
            'task_id'
        );
    }

    public function scopeStatus($query, TaskStatus $status)
    {
        return $query->where('status', $status->value);
    }

    public function scopeAssignedTo($query, Int $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    public function scopeDueBetween($query, $from, $to)
    {
        return $query->whereBeteen('due_date', [$from,$to]);
    }


}
