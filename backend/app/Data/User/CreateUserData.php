<?php

namespace App\Data\User;

use App\Enums\User\UserGender;
use App\Data\Common\EntityData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

/**
 * Data Transfer Object representing a User.
 */
class CreateUserData extends EntityData
{
    /**
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
     */
    public function __construct(
        public string $employee_code,
        public string $name,
        public string $email,

        public ?string $phone_number = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public ?\DateTime $birthday = null,

        public ?UserGender $gender = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public ?\DateTime $join_date = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public ?\DateTime $resign_date = null,

        public ?string $avatar = null,
        public bool $is_active = true,
    ) {}
}
