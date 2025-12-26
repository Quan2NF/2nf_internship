<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use App\Enums\User\UserGender;

/**
 * Filters used when listing users.
 */
class UserListFilterData extends Data
{
    /**
     * @param string|null $keyword
     * @param bool|null $is_active
     * @param UserGender|null $gender
     * @param \DateTime|null $join_from
     * @param \DateTime|null $join_to
     * @param int $page
     * @param int $per_page
     */
    public function __construct(
        public ?string $keyword = null,
        public ?bool $is_active = null,
        public ?UserGender $gender = null,

        #[WithCast('date')]
        public ?\DateTime $join_from = null,

        #[WithCast('date')]
        public ?\DateTime $join_to = null,

        public int $page = 1,
        public int $per_page = 20,
    ) {}
}
