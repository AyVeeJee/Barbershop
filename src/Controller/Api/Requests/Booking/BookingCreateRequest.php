<?php

namespace App\Controller\Api\Requests\Booking;

use DateTime;
use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingCreateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $user_id;

    #[Type('string')]
    #[NotBlank([])]
    protected $service_id;

    #[Type('string')]
    #[NotBlank([])]
    protected $employee_id;

    #[Type('string')]
    #[NotBlank([])]
    protected $begin_at;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator);
        $request = $this->getRequest();

        if ($request->get('begin_at') && !DateTime::createFromFormat('d.m.Y H:i', $request->get('begin_at'))) {
            throw new \Exception('Please check your date. Supported Format: d.m.y h:i');
        }
    }
}