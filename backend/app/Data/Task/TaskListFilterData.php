<?php

namespace App\Data\Task;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Filters used when listing tasks.
 */
class TaskListFilterData extends Data
{
    public function __construct(
        public ?int $status_id,
        public ?int $type_id,
        public ?int $priority_id,
        public ?int $assigned_to,
        public ?int $created_by,
        public ?bool $is_private,

        public ?string $keyword,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?string $start_date_from,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?string $start_date_to,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?string $due_date_from,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?string $due_date_to,

        public int $page = 1,
        public int $per_page = 15,

        public ?string $sort_by = 'id',
        public string $sort_dir = 'desc',
    ) {}
}
