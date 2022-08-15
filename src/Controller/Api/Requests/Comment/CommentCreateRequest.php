<?php

namespace App\Controller\Api\Requests\Comment;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CommentCreateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $user_id;

    #[Type('string')]
    #[NotBlank([])]
    protected $employee_id;

    #[Type('string')]
    #[NotBlank([])]
    protected $content;
}