<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking_list")
     */
    public function index(BookingRepository $bookingRepository)
    {
        $data = [];

        foreach ($bookingRepository->findAll() as $booking) {
            $data[$booking->getId()] = $booking->getName();
        }

        return $this->json($data);
    }

    /**
     * @Route("/create/{name}", name="booking_create")
     */
    public function create(BookingRepository $bookingRepository, $name)
    {
        $booking = new Booking($name);
        $bookingRepository->save($booking);
        return $this->json([
            'success' => 'created a new booking with name '.$name,
        ]);
    }
}
