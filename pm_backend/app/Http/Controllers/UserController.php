<?php

namespace App\Http\Controllers;
use App\Services\Interfaces\IUserService;
use App\Transformers\UserTransformer;
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
    public function update(Request $request, User $user) {
        $this->authorize('update', $user);
        $dto = new UpdateUserDto(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('phone'),
            $request->input('role')
        );

        $updatedUser = $this->userService->update($user, $dto);
        return response()->json(UserTransformer::transform($updatedUser));
    }
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->userService->delete($user);
        return response()->json(['message' => 'User deleted']);
    }
}