<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AssociateUsersToGroupsRequest;

class GroupUsersController extends Controller
{
    /**
     * @param AssociateUsersToGroupsRequest $request
     * @param Group $group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AssociateUsersToGroupsRequest $request, Group $group)
    {
        $user = User::findOrFail($request->user_id);

        $group->users()->save($user);

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Group $group, User $user)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $group->users()->detach($user);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
