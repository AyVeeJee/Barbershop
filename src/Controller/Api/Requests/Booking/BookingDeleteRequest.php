<?php

namespace App\Controller\Api\Requests\Booking;

use DateTime;
use App\Controller\Api\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingDeleteRequest extends BaseRequest
{
    #[Type('string')]
    #[NotBlank([])]
    protected $booking_id;
}