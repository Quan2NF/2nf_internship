<?php

namespace App\Enums;

enum ProjectStatus: int
{
    case PLANNING = 1;
    case ACTIVE = 2;
    case ON_HOLD = 3;
    case COMPLETED = 4;
    case CANCELLED = 5;

    /**
     * Get the label/display name for status
     */
    public function label(): string
    {
        return match($this) {
            self::PLANNING => 'Planning',
            self::ACTIVE => 'Active',
            self::ON_HOLD => 'On Hold',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get all statuses as key-value array
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        return [
            self::PLANNING->value => self::PLANNING->label(),
            self::ACTIVE->value => self::ACTIVE->label(),
            self::ON_HOLD->value => self::ON_HOLD->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
            self::CANCELLED->value => self::CANCELLED->label(),
        ];
    }

    /**
     * Check if status is a terminal state (completed or cancelled)
     */
    public function isTerminal(): bool
    {
        return in_array($this, [self::COMPLETED, self::CANCELLED]);
    }

    /**
     * Check if status is active (can have issues assigned)
     */
    public function isActive(): bool
    {
        return in_array($this, [self::PLANNING, self::ACTIVE]);
    }
}
