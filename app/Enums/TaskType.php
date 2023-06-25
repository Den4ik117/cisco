<?php

namespace App\Enums;

enum TaskType: string
{
    case OneAnswer = 'ONE_ANSWER';
    case MultipleAnswers = 'MULTIPLE_ANSWERS';
    case Input = 'INPUT';

    public function label(): string
    {
        return match ($this) {
            self::OneAnswer => 'Один ответ',
            self::MultipleAnswers => 'Несколько ответов',
            self::Input => 'Свободный ввод',
        };
    }
}
