<?php

namespace App\Actions\User;

use App\DTO\User\AssignRoleDTO;
use App\Models\User;

class AssignUserRole
{
    public function execute(User $user, AssignRoleDTO $dto): User
    {
        $user->syncRoles($dto->role);

        return $user->load('roles');
    }
}
