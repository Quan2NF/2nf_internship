<?php

namespace App\Http\Controllers\Api;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\DTOs\UpdateUserDto;

class UserController extends Controller {
    use AuthorizesRequests;
   protected IUserService $userService;
   public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }
    public function getall() {
        $this->authorize('viewAny', User::class);
        $users = $this->userService->getAll();
        return response()->json($users);
    }
    
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $this->userService->delete($user->id);
        return response()->json(['message' => 'Xóa người dùng']);
    }
}