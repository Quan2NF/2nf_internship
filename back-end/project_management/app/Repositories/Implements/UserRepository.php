<?php

namespace App\Repositories\Implements;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository implements IUserRepository {
   public function all() : array {
     return User::all()->toArray();

   }
   public function find(int $id): ?User
    {
        return User::find($id);
    }
    public function findbyEmail(string $email): ?User
    {
       return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = User::findOrFail($id);
        return $user->update($data);
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}