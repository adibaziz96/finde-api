<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFilterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserFilterRequest $request)
    {
        $users = $this->userService->list($request->validated());

        return UserResource::collection($users);
    }

    public function store(UserRequest $request): UserResource
    {
        $user = $this->userService->store($request->validated());

        return new UserResource($user);
    }

    public function show($id): UserResource
    {
        $user = $this->userService->show($id);

        return new UserResource($user);
    }

    public function update(UserRequest $request, $id): UserResource
    {
        $user = $this->userService->update($id, $request->validated());

        return new UserResource($user);
    }

    public function destroy($id): JsonResponse
    {
        $this->userService->destroy($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
