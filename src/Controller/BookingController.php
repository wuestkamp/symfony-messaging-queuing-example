<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Service\BookingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function create(BookingManager $bookingManager, $name)
    {
        $bookingManager->createBooking($name);
        return $this->redirectToRoute('booking_list');
    }
}
