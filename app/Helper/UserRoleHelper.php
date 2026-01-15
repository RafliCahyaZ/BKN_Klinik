<?php

namespace App\Helper;

use App\Type\UserRole;

class UserRoleHelper
{
    public static function stringToUserRole(string $userRole): UserRole
    {
        return match ($userRole) {
            UserRole::ADMIN->value => UserRole::ADMIN,
            UserRole::DOCTOR->value => UserRole::DOCTOR,
            UserRole::INVENTORY_OFFICER->value => UserRole::INVENTORY_OFFICER,
            UserRole::OFFICER->value => UserRole::OFFICER,
            'default' => throw new \Exception("User role is not valid")
        };
    }

    public static function userRoleToString(UserRole $userRole): string
    {
        return $userRole->value;
    }
}
