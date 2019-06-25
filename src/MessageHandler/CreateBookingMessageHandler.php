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
        sleep(5); // this action takes long!

        /** @var Booking $booking */
        $booking = $this->bookingRepository->findOneById($bookingMessage->getBookingId());
        $booking->setStatus(Booking::STATUS_CREATED);
        $this->bookingRepository->save($booking);
    }
}
