<?php

namespace App\DataFixtures;

use App\Entity\Cost;
use App\Entity\CostUser;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\Travel;
use App\Entity\Trip;
use App\Entity\TripUser;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        #region Data
        $arrayLocation = [];
        $arrayLocation[] = ['Gare de Strasbourg', 'Gare', 48.5850678, 7.7345492];
        $arrayLocation[] = ['Cathédrale de Strasbourg', 'Monument historique', 48.5818799, 7.7510348];
        $arrayLocation[] = ['Parc des Expositions de Strasbourg', 'Evénementiel', 48.5957929, 7.7532966];
        $arrayLocation[] = ['Volerie des Aigles', 'Tourisme', 48.2561555, 7.3866297];
        $arrayLocation[] = ['Montagne des Singes', 'Tourisme', 48.260464, 7.374915];
        $arrayLocation[] = ['Château du Haut-Koenigsbourg', 'Monument historique', 48.2494853, 7.3444831];
        $arrayLocation[] = ['Le Frankenbourg', 'Restaurant', 48.2837524, 7.3028884];
        $arrayLocation[] = ['Tellure', 'Tourisme', 48.2137384, 7.1373458];
        $arrayLocation[] = ['Parc des expositions et des Congrès de Colmar', 'Evénementiel', 48.097131, 7.360646];
        $arrayLocation[] = ['Ecomusée', 'Evénementiel', 48.1769458, 7.0193129];
        $arrayLocation[] = ["Musée de l'automobile", 'Evénementiel', 47.7610702, 7.3284082];
        $locations = []; //Liste des objects Location

        $tripNames = array('Grandes vacances 2022', 'Route des vins', 'Visite de l\'Alsace');
        $toDoListNames = array('Préparation du voyage', 'A ne pas oublier');
        $taskNames = array(
            array('Faire les courses', 'Nettoyer la voiture', 'Vérifier l\'huile', 'Vérifier la pression des pneus'),
            array('Réserver les billets des transports en commun', 'Passer au Grand Frais de Sélestat', 'Imperméabiliser les chaussures de marche'));
        $costNames = array('Dentifrice', 'Papier toilette', 'Restaurant samedi', 'Acrobranche', 'Alcool', 'Essence');//6
        $costCategories = array('Hygiène', 'Hygiène', 'Alimentaire', 'Loisir', 'Alimentaire/Loisir', 'Elevé');

        $users = [];
        $users[] = array('root@root.fr', 'Poisson', 'd\'Avril', '$2y$13$UAilrJLf.FlNU7naMk0LnefUVowtMg0Q3ojpYpK.RX1tQdPsbOCXS', (array)'ROLE_ADMIN');
        $users[] = array('canartichaud@duck.com', 'Canard', 'Tichaut', '$2y$13$UAilrJLf.FlNU7naMk0LnefUVowtMg0Q3ojpYpK.RX1tQdPsbOCXS', (array)'ROLE_USER');
        $users[] = array('XxWumpa69CortexSlayerxX@gmail.com', 'Crash', 'Bandicoot', '$2y$13$UAilrJLf.FlNU7naMk0LnefUVowtMg0Q3ojpYpK.RX1tQdPsbOCXS', (array)'ROLE_USER');
        $users[] = array('Martial.artist@gmail.com', 'Tortue', 'Ninja', '$2y$13$UAilrJLf.FlNU7naMk0LnefUVowtMg0Q3ojpYpK.RX1tQdPsbOCXS', (array)'ROLE_USER');

        #endregion

        #region Factory
        $createdUsers = [];

        foreach($users as $newUser) {
            $user = new User();
            $user->setEmail($newUser[0]);
            $user->setFirstName($newUser[1]);
            $user->setLastName($newUser[2]);
            $user->setPassword($newUser[3]);
            $user->setRoles($newUser[4]);
            $manager->persist($user);
            $createdUsers[] = $user;
        }


        for($i = 0 ; $i < count($arrayLocation) ; $i++) {
            $location = new Location();
            $location->setName($arrayLocation[$i][0]);
            $location->setType($arrayLocation[$i][1]);
            $location->setLatitude($arrayLocation[$i][2]);
            $location->setLongitude($arrayLocation[$i][3]);
            $manager->persist($location);
            $locations[$arrayLocation[$i][0]] = $location;
        }

        for($a = 0 ; $a < 3 ; $a++) {
            $trip = new Trip();
            $trip->setName($tripNames[$a]);

            foreach($createdUsers as $traveler) {
                $tripUser = new TripUser();
                $trip->addTripUser($tripUser);
                $traveler->addTripUser($tripUser);
                $manager->persist($tripUser);
            }

            for($tdl = 0 ; $tdl < 2 ; $tdl++){
                $toDoList = new ToDoList();
                $toDoList->setName($toDoListNames[$tdl]);

                $taskArray = $taskNames[$tdl];
                /*********** TASKS ***********/
                foreach ($taskArray as $taskName) {
                    $task = new Task();
                    $task->setName($taskName);
                    $task->setDescription($taskName);
                    $createdUsers[rand(0,3)]->addTask($task);
                    $task->setDate((new DateTime('now'))->modify('+2 days'));
                    $toDoList->addTask($task);
                    $manager->persist($task);
                }

                $trip->addToDoList($toDoList);
                $manager->persist($toDoList);
            }

            /*********** COSTS ***********/

            for($i = 0; $i < 5; $i++)
            {
                $user = $createdUsers[rand(0,3)];
                $cost = new Cost();
                $cost->setLabel($costNames[$i]);
                $cost->setValue(10 * $i + 5);
                $cost->setCategory($costCategories[$i]);
                $user->addCost($cost);
                $trip->addCost($cost);

                foreach($createdUsers as $createdUser) {
                    if ($createdUser !== $user) {
                        $costUser = new CostUser();
                        $cost->addCostUser($costUser);
                        $createdUser->addCostUser($costUser);
                        $manager->persist($costUser);
                    }
                }
                $manager->persist($cost);
            }

            /*********** MAP ELEMENTS ***********/

            $steps = [];
            foreach($locations as $location) {
                $step = new Step();
                $step->setLocation($location);
                $step->setDescription($location->getName());
                $createdUsers[rand(0,3)]->addStep($step);
                $trip->addStep($step);
                $manager->persist($step);
                $steps[] = $step;
            }

            for($j = 0 ; $j < count($arrayLocation) - 1; $j++) {
                $travel = new Travel();
                $travel->setStart($steps[$j]);
                $travel->setEnd($steps[$j + 1]);
                $travel->setDuration(900 * rand(1,4));
                $trip->addTravel($travel);
                $manager->persist($travel);
            }

            foreach ($steps as $step) {
                $number = rand(1,2);
                for($i = 0; $i < $number; $i++) {
                    $pointOfInterest = new PointOfInterest();
                    $delta1 = rand(1,4);
                    $sign = rand(0,1);
                    if ($sign == 1) {
                        $delta1 *= -1;
                    }
                    $delta2 = rand(1,4);
                    $sign = rand(0,1);
                    if ($sign == 1) {
                        $delta2 *= -1;
                    }
                    $location = new Location();
                    $location->setLatitude($step->getLocation()->getLatitude() + 0.05 * $delta1);
                    $location->setLongitude($step->getLocation()->getLongitude() + 0.05 * $delta2);
                    $manager->persist($location);
                    $pointOfInterest->setLocation($location);
                    $createdUsers[rand(0,3)]->addPointOfInterest($pointOfInterest);
                    $trip->addPointsOfInterest($pointOfInterest);
                    $step->addPointsOfInterest($pointOfInterest);
                    $manager->persist($pointOfInterest);
                }
            }

            $manager->persist($trip);
        }

        $manager->flush();
        #endregion
    }
}
