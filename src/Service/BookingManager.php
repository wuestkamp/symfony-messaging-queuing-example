<?php declare(strict_types=1);

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

    public function createBooking(string $name): void
    {
        $booking = new Booking($name);
        $this->bookingRepository->save($booking);
        $this->messageBus->dispatch(new CreateBookingMessage($booking->getId()));
    }

    public function findBooking(int $bookingId): ?Booking
    {
        return $this->bookingRepository->findOneById($bookingId);
    }

    public function processBooking(Booking $booking): void
    {
        sleep(5); // this action takes long!
        $booking->setStatus(Booking::STATUS_CREATED);
        $this->bookingRepository->save($booking);
    }
}
