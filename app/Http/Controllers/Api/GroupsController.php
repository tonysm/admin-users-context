<?php

namespace App\Http\Controllers\Api;

use App\Group;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
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
}
