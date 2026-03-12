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
        if (! $member->relationLoaded('user') || ! $member->relationLoaded('roles')) {
            throw new \LogicException('ProjectMember relations [user, roles] must be eager loaded.');
        }
        
        $user = $member->user;

        return new self(
            id: $user->id,
            employee_code: $user->employee_code,
            name: $user->name,
            email: $user->email,
            phone_number: $user->phone_number,
            avatar: $user->avatar,
            roles: $member->roles
                ->map(fn ($role) => [
                    'code' => $role->code,
                    'name' => $role->name,
                ])
                ->all(),
        );
    }
}
