<?php

namespace App\Controller\Api\Requests\User;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserShowAndDeleteRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $email;
}