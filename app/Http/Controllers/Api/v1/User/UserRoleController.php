<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Actions\User\AssignUserRole;
use App\DTO\User\AssignRoleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AssignRoleRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function assign(AssignRoleRequest $request, User $user, AssignUserRole $action,)
    {
        $user = $action->execute(
            $user,
            AssignRoleDTO::fromArray($request->validated())
        );

        return response()->json([
            'message' => 'Role assigned successfully.',
            'data' => $user,
        ]);
    }
}
