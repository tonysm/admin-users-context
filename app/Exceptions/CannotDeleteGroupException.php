<?php

namespace App\Exceptions;

use App\Users\Group;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CannotDeleteGroupException extends HttpException
{
    /**
     * @var Group
     */
    private $group;

    public function __construct(Group $group)
    {
        parent::__construct(Response::HTTP_UNPROCESSABLE_ENTITY, 'Cannot delete group.');

        $this->group = $group;
    }

    public function response()
    {
        return response()->json([
            'message' => $this->message,
            'errors' => [],
        ]);
    }
}
