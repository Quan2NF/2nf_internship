<?php

namespace App\Enums\Project;

enum ProjectStatus: int
{
    case PLANNED   = 1;
    case ACTIVE    = 2;
    case ONHOLD    = 3;
    case CLOSED    = 4;

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => 'Planned',
            self::ACTIVE  => 'Active',
            self::ONHOLD  => 'On-hold',
            self::CLOSED  => 'Closed',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }
}
