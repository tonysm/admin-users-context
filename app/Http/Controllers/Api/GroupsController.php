<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Users\GroupResource;
use App\Users\Group;
use App\Users\UsersContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateGroupRequest;

class GroupsController extends Controller
{
    public function index(Request $request, UsersContext $context)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        return GroupResource::collection($context->listAllGroups());
    }

    /**
     * @param CreateGroupRequest $request
     * @param UsersContext $context
     *
     * @return GroupResource
     */
    public function store(CreateGroupRequest $request, UsersContext $context)
    {
        $group = $context->createGroup($request->name);

        return new GroupResource($group);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @param UsersContext $context
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Group $group, UsersContext $context)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $context->deleteGroup($group);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
