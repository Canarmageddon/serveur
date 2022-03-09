<?php

namespace App\DataFixtures;

use App\Entity\Cost;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('root');
        $user->setFirstName('root');
        $user->setLastName('root');
        $user->setPassword('root');
        $user->setRoles('ROLE_ADMIN');
        $manager->persist($user);

        $trip = new Trip();
        $manager->persist($trip);

        for($i = 0; $i < 5; $i++)
        {
            $cost = new Cost();
            $cost->setLabel('Cost number' . $i);
            $cost->setValue(10 * $i);
            $cost->setCategory("category" . $i);
            $cost->setBeneficiaries($user->getFirstName() . $user->getLastName());
            $cost->setCreator($user);
            $cost->setTrip($trip);
            $manager->persist($cost);
        }

        $manager->flush();
    }
}
