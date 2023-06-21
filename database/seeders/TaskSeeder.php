<?php

namespace Database\Seeders;

use App\Enums\TaskType;
use App\Models\Task;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = file_get_contents(public_path('data.json'));
        $json = json_decode($content, true);

        foreach (Arr::get($json, 'content', []) as $task) {
            $newTask = Task::query()->create([
                'name' => $task[1],
//                'content' => $task[2],
//                'answer' => $task[3],
                'type' => count($task[3]) <= 1 ? TaskType::OneAnswer : TaskType::MultipleAnswers,
            ]);

            for ($i = 0; $i < count($task[2]); $i++) {
                $newTask->options()->create([
                    'name' => $task[2][$i],
                    'is_answer' => in_array($i, $task[3]),
                    'is_chosen' => null,
                ]);
            }
        }
    }
}
