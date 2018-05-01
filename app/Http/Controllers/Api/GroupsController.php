<?php

namespace App\Http\Controllers\Api;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public function store(Request $request)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);

        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'data' => $group,
        ], Response::HTTP_CREATED);
    }
}
