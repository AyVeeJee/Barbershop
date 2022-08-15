<?php

namespace App\Controller\Api\Requests\Service;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ServiceShowAndDeleteRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $service;
}