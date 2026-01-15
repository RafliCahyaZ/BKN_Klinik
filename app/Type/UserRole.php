<?php

namespace App\Type;

enum UserRole: string
{
    case ADMIN = 'admin';
    case OFFICER = 'officer';
    case INVENTORY_OFFICER = 'inventory_officer';
    case DOCTOR = 'doctor';
}
