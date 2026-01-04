<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatus;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('role', 'manager')->first();
        $user1 = User::where('email', 'user@demo.com')->first();
        $user2 = User::where('email', 'user2@demo.com')->first();


        $taskA = Task::create([
            'title' => 'Wake up',
            'description' => 'Start the day',
            'assignee_id' => $user1->id,
            'created_by' => $manager->id,
            'status' => TaskStatus::Pending,
        ]);


        $taskB = Task::create([
            'title' => 'Go to gym',
            'description' => 'Morning workout',
            'assignee_id' => $user1->id,
            'created_by' => $manager->id,
            'status' => TaskStatus::Pending,
        ]);

        $taskB->dependencies()->attach($taskA->id);


        $taskC = Task::create([
            'title' => 'Do push-ups',
            'description' => 'Strength training',
            'assignee_id' => $user2->id,
            'created_by' => $manager->id,
            'status' => TaskStatus::Pending,
        ]);

        $taskC->dependencies()->attach($taskB->id);
    }
}
