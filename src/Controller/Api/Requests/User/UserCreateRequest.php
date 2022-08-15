<?php

namespace App\Controller\Api\Requests\User;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserCreateRequest extends BaseRequest
{
    #[Type('string')]
    protected $first_name;

    #[Type('string')]
    protected $last_name;

    #[Type('string')]
    #[NotBlank([])]
    protected $email;

    #[Type('string')]
    #[NotBlank([])]
    protected $password;

    #[Type('string')]
    #[NotBlank([])]
    protected $phone;
}