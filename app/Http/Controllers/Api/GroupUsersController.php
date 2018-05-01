<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Group;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AssociateUsersToGroupsRequest;

class GroupUsersController extends Controller
{
    public function store(AssociateUsersToGroupsRequest $request, Group $group)
    {
        $user = User::findOrFail($request->user_id);

        $group->users()->save($user);

        return response()->json([], Response::HTTP_OK);
    }
}
