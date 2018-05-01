<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class GroupUsersController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $user = User::findOrFail($request->user_id);

        $group->users()->save($user);

        return response()->json([], Response::HTTP_OK);
    }
}
