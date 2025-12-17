<?php
namespace App\Transformers;
use App\Models\User;

class UserTransformer
{
    public static function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name, // example computed
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'joined_at' => $user->joined_at,
            'status' => $user->status
        ];
    }
}