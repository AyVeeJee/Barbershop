<?php

namespace App\Controller\Api\Requests\Comment;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CommentUpdateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $comment_id;

    #[Type('string')]
    protected $employee_id;

    #[Type('string')]
    protected $user_id;

    #[Type('string')]
    protected $content;
}