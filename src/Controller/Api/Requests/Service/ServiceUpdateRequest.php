<?php

namespace App\Controller\Api\Requests\Service;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ServiceUpdateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $title;

    #[Type('string')]
    protected $description;

    #[Type('string')]
    protected $price;
}