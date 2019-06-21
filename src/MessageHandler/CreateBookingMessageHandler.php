<?php

namespace App\MessageHandler;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\Repository\BookingRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateBookingMessageHandler implements MessageHandlerInterface
{
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function __invoke(CreateBookingMessage $bookingMessage)
    {
        $booking = new Booking($bookingMessage->getName());
        $this->bookingRepository->save($booking);
    }
}
