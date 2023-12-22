<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'id' => 1,
                'uuid' => '9ae94fb0-95e6-4f43-8e4a-5550f1305a0d',
                'name' => 'Применение механизмов операционных систем в разработке программного обеспечения',
                'exam_questions_number' => 25,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        ];

        Course::query()->upsert($courses, [
            'id',
            'uuid',
            'name',
            'exam_questions_number',
            'updated_at',
            'created_at',
        ]);
    }
}
