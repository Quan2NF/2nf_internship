<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;
use App\Enums\Project\ProjectStatus;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Transformers\EnumTransformer;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * DTO for filtering projects in list queries.
 */
class ProjectListFilterData extends Data
{
    /**
     * @param string|null $keyword Search by project name or code
     * @param ProjectStatus|null $status Filter by project status(es)
     * @param \DateTimeInterface|null $start_date Filter projects starting after this date
     * @param \DateTimeInterface|null $end_date Filter projects ending before this date
     * @param bool|null $is_active Filter by active projects
     * @param bool|null $is_public Filter by public projects
     * @param int|null $page Pagination page number
     * @param int|null $per_page Number of items per page
     */
    public function __construct(
        public ?string $keyword = null,
        #[WithTransformer(EnumTransformer::class)]
        public ?ProjectStatus $status = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTimeInterface $start_date = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public ?\DateTimeInterface $end_date = null,
        
        public ?bool $is_active = null,
        public ?bool $is_public = null,
        public ?int $page = 1,
        public ?int $per_page = 20,
    ) {}
}
