<?php

namespace App\Controller\Api\Requests\Employee;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EmployeeUpdateRequest extends BaseRequest
{
    #[Type('string')]
    protected $first_name;

    #[Type('string')]
    protected $last_name;

    #[Type('string')]
    #[NotBlank([])]
    protected $email;

    #[Type('string')]
    protected $phone;
}