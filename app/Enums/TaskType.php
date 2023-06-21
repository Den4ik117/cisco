<?php

namespace App\Enums;

enum TaskType: string
{
    case OneAnswer = 'ONE_ANSWER';
    case MultipleAnswers = 'MULTIPLE_ANSWERS';

    public function label(): string
    {
        return match ($this) {
            self::OneAnswer => 'Один ответ',
            self::MultipleAnswers => 'Несколько ответов',
        };
    }
}
