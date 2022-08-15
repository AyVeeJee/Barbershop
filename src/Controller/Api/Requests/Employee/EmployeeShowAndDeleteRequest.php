<?php

namespace App\Controller\Api\Requests\Employee;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EmployeeShowAndDeleteRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $email;
}