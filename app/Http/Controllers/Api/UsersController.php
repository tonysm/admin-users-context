<?php

namespace App\Http\Controllers\Api;

use App\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;

class UsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Users\CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'data' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, User $user)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
