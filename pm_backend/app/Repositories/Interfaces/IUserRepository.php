<?php
namespace App\Repositories\Interfaces;
use App\Models\User;
interface IUserRepository
{
    public function all(): array;
    public function find(int $id): ?User;
    public function findbyEmail(string $email) : ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}