<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Album;
use App\Entity\Cost;
use App\Entity\Document;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\Travel;
use App\Entity\Step;
use App\Entity\PointOfInterest;
use App\Entity\Trip;
use App\Entity\User;

class TripUserVoter extends Voter
{
    public const EDIT = 'TRIP_EDIT';
    public const VIEW = 'TRIP_VIEW';
    private const GETTRIPCLASSES = [Album::class, Cost::class, LogBookEntry::class,
        Picture::class,  ToDoList::class, Travel::class, Step::class,
        PointOfInterest::class];

    protected function supports(string $attribute, $subject): bool
    {
        $supported_classes = array_merge(self::GETTRIPCLASSES, [Document::class, Trip::class, Task::class, User::class]);
        foreach ($supported_classes as $class) {
            if($subject instanceof $class){
                return in_array($attribute, [self::EDIT, self::VIEW]);
            }
        }
        return false;

    }


    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if ($user instanceof User) {


            switch ($attribute) {
                case self::EDIT:
                    $trips = $user->getTrips();

                    foreach (self::GETTRIPCLASSES as $class) {
                        if($subject instanceof $class){
                            return in_array($subject->getTrip(), $trips);
                        }
                    }
                    if($subject instanceof Document) {
                        return in_array($subject->getMapElement()->getTrip(), $trips);
                    }
                    elseif($subject instanceof Trip) {
                        return in_array($subject, $trips);
                    }
                    elseif($subject instanceof  Task){
                        return in_array($subject->getToDoList()->getTrip(), $trips);
                    }
                    elseif($subject instanceof User){
                        return $user->getEmail() === $subject->getEmail();
                    }
                    break;
            }
        }

        return false;
    }
}
