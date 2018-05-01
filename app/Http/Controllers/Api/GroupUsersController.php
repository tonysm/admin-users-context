<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class GroupUsersController extends Controller
{
    public function store(Request $request, Group $group)
    {
        abort_unless($request->user()->is_admin, Response::HTTP_FORBIDDEN);
        
        $this->validate($request, [
            'user_id' => [
                Rule::unique('group_user', 'user_id')
                    ->where('group_id', $group->id)
            ],
        ]);

        $user = User::findOrFail($request->user_id);

        $group->users()->save($user);

        return response()->json([], Response::HTTP_OK);
    }
}
