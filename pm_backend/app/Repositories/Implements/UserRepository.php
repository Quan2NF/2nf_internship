<?php

namespace App\Repositories\Implements;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\Implements\BaseRepository;

class UserRepository extends BaseRepository implements IUserRepository 
{
   public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query()
            ->where('email', $email)
            ->first();
    }
}