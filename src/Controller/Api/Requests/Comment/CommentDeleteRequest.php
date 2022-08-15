<?php

namespace App\Controller\Api\Requests\Comment;

use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CommentDeleteRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $comment_id;
}