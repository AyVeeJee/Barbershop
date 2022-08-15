<?php

namespace App\Controller\Api\Requests\Booking;

use App\Controller\Api\Requests\BaseRequest;
use DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingShowRequest extends BaseRequest
{
    #[Type('string')]
    protected $booking_id;

    #[Type('string')]
    protected $user_id;

    #[Type('string')]
    protected $service_id;

    #[Type('string')]
    protected $employee_id;

    #[Type('string')]
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