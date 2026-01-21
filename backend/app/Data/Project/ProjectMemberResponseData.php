<?php

namespace App\Data\Project;

use Spatie\LaravelData\Data;
use App\Models\ProjectMember;

class ProjectMemberResponseData extends Data
{
    public function __construct(
        public int $id,
        public string $employee_code,
        public string $name,
        public string $email,
        public ?string $phone_number = null,
        public ?string $avatar = null,
        public ?array $roles = null,
    ) {}

    public static function fromProjectMember(ProjectMember $member): self
    {
        return new self(
            id: $member->user->id,
            employee_code: $member->user->employee_code,
            name: $member->user->name,
            email: $member->user->email,
            phone_number: $member->user->phone_number,
            avatar: $member->user->avatar,
            roles: $member->roles
                ->map(fn ($role) => [
                    'code' => $role->code,
                    'name' => $role->name,
                ])
                ->all(),
        );
    }
}
