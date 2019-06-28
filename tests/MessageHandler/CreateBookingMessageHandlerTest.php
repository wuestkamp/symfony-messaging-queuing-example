<?php declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\MessageHandler\CreateBookingMessageHandler;
use App\Repository\BookingRepository;
use PHPUnit\Framework\TestCase;

class CreateBookingMessageHandlerTest extends TestCase
{
    /**
     * @group time-sensitive
     * @throws \Exception
     */
    public function testInvoke(): void
    {
        // first we create our first booking
        $booking = new Booking('my-new-booking');
        $booking->setId(1);

        // now we pass this booking into a CreateBookingMessage
        $createBookingMessage = new CreateBookingMessage($booking->getId());

        // to mock the CreateBookingMessageHandler, we need a mock of the BookingRepository
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->setMethods(['findOneById', 'save']) // list of methods we would like to mock
            ->disableOriginalConstructor()
            ->getMock();

        // we create an instance of the CreateBookingMessageHandler
        $createBookingMessageHandler = new CreateBookingMessageHandler($bookingRepositoryMock);

        // the $bookingRepositoryMock should return our booking if the method findOneById is called
        $bookingRepositoryMock->expects($this->once())
            ->method('findOneById')
            ->with($booking->getId())
            ->willReturn($booking)
        ;
        // we also expect the save method to be called on the $bookingRepositoryMock
        $bookingRepositoryMock->expects($this->once())->method('save')->with($booking);

        // the status should be new before the handler processes our message
        $this->assertEquals(Booking::STATUS_NEW, $booking->getStatus());

        // now we actually pass the CreateBookingMessage to the CreateBookingMessageHandler, this works by
        // calling the CreateBookingMessageHandler as a function, which then triggers its __invoke method
        $createBookingMessageHandler($createBookingMessage);

        // the status should now be created
        $this->assertEquals(Booking::STATUS_CREATED, $booking->getStatus());
    }
}
