<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Message\CreateBookingMessage;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/bookings", name="booking_list")
     */
    public function index(BookingRepository $bookingRepository)
    {
        $data = [];

        foreach ($bookingRepository->findAll() as $booking) {
            $data[$booking->getId()] = [
                'name' => $booking->getName(),
                'status' => $booking->getStatus(),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/bookings/create/{name}", name="booking_create")
     */
    public function create(MessageBusInterface $messageBus, BookingRepository $bookingRepository, $name)
    {
        $booking = new Booking($name);
        $bookingRepository->save($booking);
        $messageBus->dispatch(new CreateBookingMessage($booking->getId()));
        return $this->redirectToRoute('booking_list');
    }
}
