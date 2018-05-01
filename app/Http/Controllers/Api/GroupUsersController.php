<?php

namespace App\Http\Controllers\Api;

use App\Users\User;
use App\Users\Group;
use App\Users\UsersContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AssociateUsersToGroupsRequest;

class GroupUsersController extends Controller
{
    /**
     * @param AssociateUsersToGroupsRequest $request
     * @param Group $group
     * @param UsersContext $context
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AssociateUsersToGroupsRequest $request, Group $group, UsersContext $context)
    {
        $context->addUserToGroup($request->user_id, $group);

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @param User $user
     * @param UsersContext $context
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Group $group, User $user, UsersContext $context)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $context->removeUserFromGroup($user, $group);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
