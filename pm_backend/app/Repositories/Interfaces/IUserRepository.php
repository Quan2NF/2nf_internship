<?php
namespace App\Repositories\Interfaces;
use App\Models\User;
interface IUserRepository
{
    public function findbyEmail(string $email) : ?User;
}