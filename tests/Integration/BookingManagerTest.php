<?php declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\Repository\BookingRepository;
use App\Service\BookingManager;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Doctrine\DoctrineReceiver;
use Symfony\Component\Messenger\Worker;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookingManagerTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();
    }

    /**
     * @throws \Exception
     */
    public function testCreateBookingProcess(): void
    {
        // first we clear the database
        DatabaseCleaner::prime(self::$kernel);

        // now we get all services directly from the container we will need for the test

        /** @var Container $container */
        $container = self::$container; // this allows us to access even private services directly

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();

        /** @var BookingRepository $bookingRepository */
        $bookingRepository = $em->getRepository(Booking::class);

        /** @var BookingManager $bookingManager */
        $bookingManager = $container->get(BookingManager::class);

        /* @var DoctrineReceiver $doctrineReceiver */
        $doctrineReceiver = $container->get('messenger.transport.async');

        // now it gets interesting, we instantiate an instance of the Symfony Worker to be executed!
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        $logger = $container->get(LoggerInterface::class);
        $messageBus = $container->get(MessageBusInterface::class);
        $worker = new Worker([$doctrineReceiver], $messageBus, [], $eventDispatcher, $logger);
        $worker = new Worker\StopWhenMessageCountIsExceededWorker($worker, 1, $logger);

        // create a new booking
        $bookingManager->createBooking('my-amazing-booking');

        // assure booking was created
        $booking = $bookingRepository->findOneBy([]);
        $this->assertEquals(1, $bookingRepository->count([]));
        $this->assertEquals('my-amazing-booking', $booking->getName());
        $this->assertEquals(Booking::STATUS_NEW, $booking->getStatus());

        // assure a CreateBookingMessage was send and received by the messenger doctrine transport
        $this->assertEquals(1, $doctrineReceiver->getMessageCount());
        $envelop = $doctrineReceiver->all()->current();
        $this->assertEquals(CreateBookingMessage::class, get_class($envelop->getMessage()));

        // run the worker process. this simulates the process usually run in the background by a worker
        $worker->run(); // executed synchronous here

        // assure message was processed
        $this->assertEquals(0, $doctrineReceiver->getMessageCount());

        // assure booking was updated
        $booking = $bookingRepository->findOneBy([]);
        $this->assertEquals(1, $bookingRepository->count([]));
        $this->assertEquals('my-amazing-booking', $booking->getName());
        $this->assertEquals(Booking::STATUS_CREATED, $booking->getStatus());
    }
}
