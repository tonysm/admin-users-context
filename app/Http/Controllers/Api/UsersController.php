<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

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
