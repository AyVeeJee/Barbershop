<?php

namespace App\Controller\Api\Requests\Employee;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EmployeeCreateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $first_name;

    #[Type('string')]
    #[NotBlank([])]
    protected $last_name;

    #[Type('string')]
    #[NotBlank([])]
    protected $email;

    #[Type('string')]
    #[NotBlank([])]
    protected $phone;
}