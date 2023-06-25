<?php

namespace App\Enums;

enum TestType: string
{
    case Exam = 'EXAM';
    case Module = 'MODULE';
    case Marathon = 'MARATHON';
    case Mistake = 'MISTAKE';

    public function label(): string
    {
        return match ($this) {
            self::Exam => 'Экзамен',
            self::Module => 'Тесты по модулю',
            self::Marathon => 'Марафон',
            self::Mistake => 'Задачи с ошибками',
        };
    }
}
