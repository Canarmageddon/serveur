<?php

namespace App\DataFixtures;

use App\Entity\Cost;
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
        $locations = []; //Liste des objects Location

        $tripNames = array('Grandes vacances 2022', 'Visite de l\'Alsace', 'On emmène Mémé balader');
        $toDoListNames = array('Préparation du voyage', 'Choses à faire à la Montagne des Singes', 'A ne pas oublier de faire', 'Médicaments de Mémé');
        $taskNames = array('Faire les courses', 'Nettoyer la voiture', 'Vérifier l\'huile', 'Vérifier la pression des pneus', 'Huiler le Youpala de Mémé');//5
        $costNames = array('Dentifrice', 'Papier toilette', 'Restaurant samedi', 'Acrobranche', 'Alcool', 'Essence');//6
        $costCategories = array('Hygiène', 'Hygiène', 'Alimentaire', 'Loisir', 'Alimentaire/Loisir', 'Elevé');

        $users = [];
        $users[] = array('root@root.fr', 'Poisson', 'd\'Avril', 'mdp', (array)'ROLE_ADMIN');
        $users[] = array('canartichaud@duck.com', 'Canard', 'Tichaut', 'mdp', (array)'ROLE_USER');
        $users[] = array('XxWumpa69CortexSlayerxX@gmail.com', 'Crash', 'Bandicoot', 'mdp', (array)'ROLE_USER');
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
            $locations[] = $location;
        }

        $roles = ['admin', 'editor', 'guest'];

        for($a = 0 ; $a < 3 ; $a++) {
            $trip = new Trip();
            $trip->setName($tripNames[$a]);

            $idRole = 0;
            foreach($createdUsers as $traveler) {
                $tripUser = new TripUser();
                $trip->addTripUser($tripUser);
                $traveler->addTripUser($tripUser);
                $tripUser->setRole($roles[$idRole]);
                $manager->persist($tripUser);
                $idRole++;
            }

            for($tdl = 0 ; $tdl < 3 ; $tdl++){
                $toDoList = new ToDoList();
                $toDoList->setName($toDoListNames[$tdl]);

                /*********** TASKS ***********/
                for($i = 0 ; $i < 4 ; $i++) {
                    $task = new Task();
                    $task->setName($taskNames[$i]);
                    $task->setDescription($taskNames[$i]);
                    $task->setCreator($user);
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
                $cost = new Cost();
                $cost->setLabel($costNames[$i]);
                $cost->setValue(10 * $i + 5);
                $cost->setCategory($costCategories[$i]);
                $cost->setBeneficiaries($user);
                $cost->setCreator($user);
                $trip->addCost($cost);
                $manager->persist($cost);
            }

            /*********** MAP ELEMENTS ***********/

            for($j = 0 ; $j < 7; $j++) {
                $pointOfInterest = new PointOfInterest();
                $pointOfInterest->setLocation($locations[$j]);
                $pointOfInterest->setDescription($locations[$j]->getName());
                $pointOfInterest->setCreator($user);
                $trip->addPointsOfInterest($pointOfInterest);
                $manager->persist($pointOfInterest);
            }

            $steps = [];
            for($j = 0 ; $j < 4; $j++) {
                $step = new Step();
                $step->setLocation($locations[$j + 7]);
                $step->setDescription($locations[$j + 7]->getName());
                $step->setCreator($user);
                $trip->addStep($step);
                $manager->persist($step);
                $steps[] = $step;
            }

            for($j = 0 ; $j < 3; $j++) {
                $travel = new Travel();
                $travel->setStart($steps[$j]);
                $travel->setEnd($steps[$j + 1]);
                $travel->setDuration(3600 * ($i+1));
                $trip->addTravel($travel);
                $manager->persist($travel);
            }

            $manager->persist($trip);
        }

        $manager->flush();
        #endregion
    }
}
