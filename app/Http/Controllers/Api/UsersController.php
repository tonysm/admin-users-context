<?php

namespace App\Http\Controllers\Api;

use App\Users\User;
use App\Users\UsersContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Http\Requests\Users\CreateUserRequest;

class UsersController extends Controller
{
    public function index(Request $request, UsersContext $context)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        return UserResource::collection($context->listUsersWithGroupsPaginated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Users\CreateUserRequest $request
     * @param \App\Users\UsersContext $context
     *
     * @return UserResource
     */
    public function store(CreateUserRequest $request, UsersContext $context)
    {
        $user = $context->createUser($request->name);

        return new UserResource($user);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param UsersContext $context
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, User $user, UsersContext $context)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $context->deleteUser($user);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
