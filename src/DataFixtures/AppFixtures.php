<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Cost;
use App\Entity\Itinerary;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\Travel;
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
        $arrayLocation = [];
        $arrayLocation[] = ['Gare de Strasbourg', 'Gare', 48.5850678, 7.7345492];
        $arrayLocation[] = ['Château du Haut-Koenigsbourg', 'Monument historique', 48.2494853, 7.3444831];
        $arrayLocation[] = ['Volerie des Aigles', 'Tourisme', 48.2561555, 7.3866297];
        $arrayLocation[] = ['Montagne des Singes', 'Tourisme', 48.260464, 7.374915];
        $arrayLocation[] = ['Cathédrale de Strasbourg', 'Monument historique', 48.5818799, 7.7510348];
        $arrayLocation[] = ['Le Frankenbourg', 'Restaurant', 48.2837524, 7.3028884];
        $arrayLocation[] = ['Parc des Expositions de Strasbourg', 'Evénementiel', 48.5957929, 7.7532966];
        $arrayLocation[] = ['Parc des expositions et des Congrès de Colmar', 'Evénementiel', 48.097131, 7.360646];
        $arrayLocation[] = ['Ecomusée', 'Evénementiel', 48.1769458, 7.0193129];
        $arrayLocation[] = ["Musée de l'automobile", 'Evénementiel', 47.7610702, 7.3284082];
        $arrayLocation[] = ['Tellure', 'Tourisme', 48.2137384, 7.1373458];
        $locations = [];

        $user = new User();
        $user->setEmail('root');
        $user->setFirstName('root');
        $user->setLastName('root');
        $user->setPassword('root');
        $user->setRoles((array)'ROLE_ADMIN');
        $manager->persist($user);

        for($i = 0 ; $i < count($arrayLocation) ; $i++) {
            $location = new Location();
            $location->setName($arrayLocation[$i][0]);
            $location->setType($arrayLocation[$i][1]);
            $location->setLatitude($arrayLocation[$i][2]);
            $location->setLongitude($arrayLocation[$i][3]);
            $manager->persist($location);
            $locations[] = $location;
        }

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
                $task->setLocation($locations[$i]);
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

            for($i = 0 ; $i < 3 ; $i++) {
                $itinerary = new Itinerary();
                $itinerary->setDescription('Itinéraire ' . $i);
                for($j = 0 ; $j < 7; $j++) {
                    $pointOfInterest = new PointOfInterest();
                    $pointOfInterest->setDescription('Point of Interest ' . $j);
                    $pointOfInterest->setCreator($user);
                    $pointOfInterest->setLocation($locations[$j]);
                    $itinerary->addPointsOfInterest($pointOfInterest);
                    $manager->persist($pointOfInterest);
                }

                for($j = 0 ; $j < 4; $j++) {
                    $step = new Step();
                    $step->setDescription('Step ' . $j);
                    $step->setCreator($user);
                    $step->setLocation($locations[$j + 7]);
                    $itinerary->addStep($step);
                    $manager->persist($step);
                }

                for($j = 0 ; $j < 3; $j++) {
                    $travel = new Travel();
                    $travel->setStart($locations[$j +7]);
                    $travel->setEnd($locations[$j +8]);
                    $travel->setDuration(3600 * ($i+1));
                    $itinerary->addTravel($travel);
                    $manager->persist($travel);
                }

                $trip->addItinerary($itinerary);
                $manager->persist($itinerary);
            }

            $manager->persist($trip);
        }

        $manager->flush();
    }
}
