<?php

namespace Database\Seeders;

use App\Enums\TaskType;
use App\Models\Option;
use App\Models\Task;
use Illuminate\Database\Seeder;
use mysql_xdevapi\Collection;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = file_get_contents(public_path('course-one.json'));
        $tasks = json_decode($content, true);
        $courseId = 1;

        foreach ($tasks as $task) {
            $taskModel = Task::query()->updateOrCreate([
                'name' => $task['Name'],
                'course_id' => $courseId,
            ], [
                'type' => $this->getTaskType($task['Answers']),
            ]);

            foreach ($task['Answers'] as $option) {
                Option::query()->updateOrCreate([
                    'name' => $option['Name'],
                    'task_id' => $taskModel->id,
                ], [
                    'is_answer' => $option['IsAnswer'],
                ]);
            }
        }
    }

    private function getTaskType(array $answers): TaskType
    {
        $answers = collect($answers);

        $trueAnswers = $answers->filter(fn($answer) => $answer['IsAnswer']);

        if ($trueAnswers->count() === 1) return TaskType::OneAnswer;

        return TaskType::MultipleAnswers;
    }
}
