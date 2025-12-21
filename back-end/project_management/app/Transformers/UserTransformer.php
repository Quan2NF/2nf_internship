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
        ];
    }
}