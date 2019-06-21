<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $b1 = new Booking('booking1');
        $b2 = new Booking('another');
        $b3 = new Booking('good-trip');

        $manager->persist($b1);
        $manager->persist($b2);
        $manager->persist($b3);

        $manager->flush();
    }
}
