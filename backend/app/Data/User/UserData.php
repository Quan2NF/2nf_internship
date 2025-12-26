<?php

namespace App\Data\User;

use App\Data\Common\EntityData;
use App\Enums\User\UserGender;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * Data Transfer Object representing a User.
 */
class UserData extends EntityData
{
    /**
     * @param int $id
     * @param string $employee_code
     * @param string $name
     * @param string $email
     * @param string|null $phone_number
     * @param \DateTime|null $birthday
     * @param UserGender|null $gender
     * @param \DateTime|null $join_date
     * @param \DateTime|null $resign_date
     * @param string|null $avatar
     * @param bool $is_active
     * @param \DateTime $created_at
     * @param \DateTime $updated_at
     */
    public function __construct(
        public int $id,
        public string $employee_code,
        public string $name,
        public string $email,

        public ?string $phone_number = null,

        #[WithCast('date')]
        public ?\DateTime $birthday = null,

        public ?UserGender $gender = null,

        #[WithCast('date')]
        public ?\DateTime $join_date = null,

        #[WithCast('date')]
        public ?\DateTime $resign_date = null,

        public ?string $avatar = null,
        public bool $is_active = true,

        #[WithCast('datetime')]
        public \DateTime $created_at,

        #[WithCast('datetime')]
        public \DateTime $updated_at,
    ) {}
}
