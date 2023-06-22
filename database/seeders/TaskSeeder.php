<?php

namespace Database\Seeders;

use App\Enums\TaskType;
use App\Models\Task;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            preg_match('/TEMP="(.+\.(png|jpg|jpeg))"/', $task[1], $matches);
            $filename = null;

            if (isset($matches[1])) {
                $path = "https://mintbrain.github.io/ciscoTest/pics/$matches[1]";
                $uuid = Str::orderedUuid()->toString();
                $filename = "/images/$uuid.$matches[2]";
                Storage::put($filename, file_get_contents($path));
            }

            $newTask = Task::query()->create([
                'name' => preg_replace('/TEMP="(.+\.(png|jpg|jpeg))"/', '', $task[1]),
                'image_content' => $filename,
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
