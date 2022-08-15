<?php

namespace App\Controller\Api\Requests\User;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserUpdateRequest extends BaseRequest
{
    #[Type('string')]
    protected $first_name;

    #[Type('string')]
    protected $last_name;

    #[Type('string')]
    #[NotBlank([])]
    protected $email;

    #[Type('string')]
    protected $password;

    #[Type('string')]
    protected $phone;
}