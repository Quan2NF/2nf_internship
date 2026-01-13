<?php

namespace App\Enums;

enum IssueType: int
{
    case BUG = 1;
    case FEATURE = 2;
    case IMPROVEMENT = 3;
    case TASK = 4;

    public function label(): string
    {
        return match ($this) {
            self::BUG => 'Bug',
            self::FEATURE => 'Feature',
            self::IMPROVEMENT => 'Improvement',
            self::TASK => 'Task',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}

