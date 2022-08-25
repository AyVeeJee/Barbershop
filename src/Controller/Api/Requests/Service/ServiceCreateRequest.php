<?php

namespace App\Controller\Api\Requests\Service;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ServiceCreateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $title;

    #[Type('string')]
    #[NotBlank([])]
    protected $description;

    #[Type('string')]
    #[NotBlank([])]
    protected $price;
}