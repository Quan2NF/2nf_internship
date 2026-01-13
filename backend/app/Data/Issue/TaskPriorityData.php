<?php

namespace App\Data\Issue;

use App\Models\TaskPriority;
use Spatie\LaravelData\Data;

class TaskPriorityData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $sort,
        public int $is_active,
    ) {}

    public static function fromModel(TaskPriority $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            sort: $model->sort,
            is_active: $model->is_active,
        );
    }

    public static function fromModels($models): \App\Data\Collections\TaskPriorityDataCollection
    {
        $items = [];
        foreach ($models as $model) {
            $items[] = self::fromModel($model);
        }
        return new \App\Data\Collections\TaskPriorityDataCollection($items);
    }
}
