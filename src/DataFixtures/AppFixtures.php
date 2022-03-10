<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Cost;
use App\Entity\Location;
use App\Entity\Task;
use App\Entity\Trip;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
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
        $user->setRoles((array)'ROLE_ADMIN');
        $manager->persist($user);

        $location = new Location();
        $location->setName('Gare de Strasbourg');
        $location->setType('Gare');
        $location->setLatitude(48.5850678);
        $location->setLongitude(7.7345492);
        $manager->persist($location);

        for($a = 1 ; $a < 4 ; $a++) {
            $trip = new Trip();
            $trip->setName('Voyage numéro ' . $a);
            $trip->addTraveler($user);

            /*********** TASKS ***********/
            for($i = 1 ; $i < 5 ; $i++) {
                $task = new Task();
                $task->setName('Tâche ' . $i);
                $task->setDescription('description tâche ' . $i);
                $task->setCreator($user);
                $task->setDate((new DateTime('now'))->modify('+2 days'));
                $task->setLocation($location);
                $trip->addTask($task);
                $manager->persist($task);
            }

            /*********** COSTS ***********/
            for($i = 1; $i < 6; $i++)
            {
                $cost = new Cost();
                $cost->setLabel('Cost number ' . $i);
                $cost->setValue(10 * $i);
                $cost->setCategory("category " . $i);
                $cost->setBeneficiaries($user->getFirstName() . $user->getLastName());
                $cost->setCreator($user);
                $trip->addCost($cost);
                $manager->persist($cost);
            }

            /*********** ITINERARY ***********/

            $manager->persist($trip);
        }

        $manager->flush();
    }
}
