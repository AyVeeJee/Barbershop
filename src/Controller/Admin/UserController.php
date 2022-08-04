<?php

namespace App\Controller\Admin;

use App\Entity\User;

class UserController extends UserCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
}
