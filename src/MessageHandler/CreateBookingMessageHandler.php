<?php declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\Service\BookingManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateBookingMessageHandler implements MessageHandlerInterface
{
    private $bookingManager;

    public function __construct(BookingManager $bookingManager)
    {
        $this->bookingManager = $bookingManager;
    }

    public function __invoke(CreateBookingMessage $bookingMessage)
    {
        $booking = $this->bookingManager->findBooking($bookingMessage->getBookingId());

        if ($booking instanceof Booking && $booking->getStatus() === Booking::STATUS_NEW) {
            $this->bookingManager->processBooking($booking);
        }
    }
}
