<?php declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\MessageHandler\CreateBookingMessageHandler;
use App\Service\BookingManager;
use PHPUnit\Framework\TestCase;

class CreateBookingMessageHandlerTest extends TestCase
{
    /**
     * This will test a booking being processed
     * @group time-sensitive
     * @throws \Exception
     */
    public function testProcessBooking(): void
    {
        // first we create our first booking
        $booking = new Booking('my-new-booking');
        $booking->setId(1);

        // now we pass this booking into a CreateBookingMessage
        $createBookingMessage = new CreateBookingMessage($booking->getId());

        // to mock the CreateBookingMessageHandler, we need a mock of the BookingManager
        $bookingManagerMock = $this->getMockBuilder(BookingManager::class)
            ->setMethods(['findBooking', 'processBooking']) // list of methods we would like to mock
            ->disableOriginalConstructor()
            ->getMock();

        // the $bookingManagerMock should return our booking if the method findOneById is called
        $bookingManagerMock->expects($this->once())
            ->method('findBooking')
            ->with($booking->getId())
            ->willReturn($booking)
        ;

        // we expect the processBooking method to be called on the $bookingManagerMock
        $bookingManagerMock->expects($this->once())->method('processBooking')->with($booking);

        $this->assertEquals(Booking::STATUS_NEW, $booking->getStatus());

        // we create an instance of the CreateBookingMessageHandler
        $createBookingMessageHandler = new CreateBookingMessageHandler($bookingManagerMock);

        // now we actually pass the CreateBookingMessage to the CreateBookingMessageHandler, this works by
        // calling the CreateBookingMessageHandler as a function, which then triggers its __invoke method
        $createBookingMessageHandler($createBookingMessage);
    }

    /**
     * This will test a booking not being processed
     * @group time-sensitive
     * @throws \Exception
     */
    public function testNoProcessBooking(): void
    {
        // first we create our first booking
        $booking = new Booking('my-new-booking');
        $booking->setId(1);
        $booking->setStatus(Booking::STATUS_CREATED);

        // now we pass this booking into a CreateBookingMessage
        $createBookingMessage = new CreateBookingMessage($booking->getId());

        // to mock the CreateBookingMessageHandler, we need a mock of the BookingManager
        $bookingManagerMock = $this->getMockBuilder(BookingManager::class)
            ->setMethods(['findBooking', 'processBooking']) // list of methods we would like to mock
            ->disableOriginalConstructor()
            ->getMock();

        // the $bookingManagerMock should return our booking if the method findOneById is called
        $bookingManagerMock->expects($this->once())
            ->method('findBooking')
            ->with($booking->getId())
            ->willReturn($booking)
        ;
        // we do NOT expect the processBooking method to be called on the $bookingManagerMock
        $bookingManagerMock->expects($this->never())->method('processBooking')->with($booking);

        $this->assertEquals(Booking::STATUS_CREATED, $booking->getStatus());

        // we create an instance of the CreateBookingMessageHandler
        $createBookingMessageHandler = new CreateBookingMessageHandler($bookingManagerMock);

        // now we actually pass the CreateBookingMessage to the CreateBookingMessageHandler, this works by
        // calling the CreateBookingMessageHandler as a function, which then triggers its __invoke method
        $createBookingMessageHandler($createBookingMessage);
    }
}
