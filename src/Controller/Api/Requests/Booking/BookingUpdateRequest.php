<?php

namespace App\Controller\Api\Requests\Booking;

use DateTime;
use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingUpdateRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $booking_id;

    #[Type('string')]
    protected $user_id;

    #[Type('string')]
    protected $service_id;

    #[Type('string')]
    protected $employee_id;

    #[Type('string')]
    protected $begin_at;
}