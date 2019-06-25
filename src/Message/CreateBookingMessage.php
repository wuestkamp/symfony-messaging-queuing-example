<?php

namespace App\Message;

class CreateBookingMessage
{
    /**
     * @var int
     */
    private $bookingId;

    public function __construct(int $bookingId)
    {
        $this->bookingId = $bookingId;
    }

    /**
     * @return int
     */
    public function getBookingId(): int
    {
        return $this->bookingId;
    }
}
