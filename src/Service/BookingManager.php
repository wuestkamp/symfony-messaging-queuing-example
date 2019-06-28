<?php

namespace App\Service;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\Repository\BookingRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class BookingManager
{
    private $messageBus;
    private $bookingRepository;

    public function __construct(MessageBusInterface $messageBus, BookingRepository $bookingRepository)
    {
        $this->messageBus = $messageBus;
        $this->bookingRepository = $bookingRepository;
    }

    public function createBooking(string $name)
    {
        $booking = new Booking($name);
        $this->bookingRepository->save($booking);
        $this->messageBus->dispatch(new CreateBookingMessage($booking->getId()));
    }
}
