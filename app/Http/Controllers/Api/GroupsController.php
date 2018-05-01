<?php

namespace App\Http\Controllers\Api;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotDeleteGroupException;
use App\Http\Requests\Users\CreateGroupRequest;

class GroupsController extends Controller
{
    /**
     * @param CreateGroupRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateGroupRequest $request)
    {
        $group = Group::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'data' => $group,
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Group $group
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Group $group)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        if ($group->users()->count() > 0) {
            throw new CannotDeleteGroupException($group);
        }

        $group->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
