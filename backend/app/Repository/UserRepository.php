<?php

namespace App\Repository;

use App\Models\User;
use App\Contracts\Repository\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
