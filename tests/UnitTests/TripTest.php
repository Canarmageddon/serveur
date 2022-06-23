<?php

namespace App\Tests\UnitTests;

use App\Entity\Album;
use App\Entity\Cost;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\ToDoList;
use App\Entity\Travel;
use App\Entity\Trip;
use App\Entity\TripUser;
use PHPUnit\Framework\TestCase;

class TripTest extends TestCase
{
    private Trip $trip;

    public function setUp(): void
    {
        parent::setUp();

        $this->trip = new Trip();
    }

    public function testGetAlbum() : void
    {
        $value = new Album();

        $value->setTrip($this->trip);

        $response = $this->trip->setAlbum($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertEquals($value, $this->trip->getAlbum());
    }

    public function testGetCosts(): void
    {
        $value = new Cost();
        $value1 = new Cost();
        $value2 = new Cost();

        $this->trip->addCost($value);
        $this->trip->addCost($value1);
        $this->trip->addCost($value2);

        self::assertCount(3, $this->trip->getCosts());
        self::assertTrue($this->trip->getCosts()->contains($value));
        self::assertTrue($this->trip->getCosts()->contains($value1));
        self::assertTrue($this->trip->getCosts()->contains($value2));

        $response = $this->trip->removeCost($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getCosts());
        self::assertFalse($this->trip->getCosts()->contains($value));
        self::assertTrue($this->trip->getCosts()->contains($value1));
        self::assertTrue($this->trip->getCosts()->contains($value2));
    }

    public function testGetName() : void
    {
        $value = 'name';

        $response = $this->trip->setName($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertEquals($value, $this->trip->getName());
    }

    public function testGetToDoLists(): void
    {
        $value = new ToDoList();
        $value1 = new ToDoList();
        $value2 = new ToDoList();

        $this->trip->addToDoList($value);
        $this->trip->addToDoList($value1);
        $this->trip->addToDoList($value2);

        self::assertCount(3, $this->trip->getToDoLists());
        self::assertTrue($this->trip->getToDoLists()->contains($value));
        self::assertTrue($this->trip->getToDoLists()->contains($value1));
        self::assertTrue($this->trip->getToDoLists()->contains($value2));

        $response = $this->trip->removeToDoList($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getToDoLists());
        self::assertFalse($this->trip->getToDoLists()->contains($value));
        self::assertTrue($this->trip->getToDoLists()->contains($value1));
        self::assertTrue($this->trip->getToDoLists()->contains($value2));
    }

    public function testGetPointsOfInterest(): void
    {
        $value = new PointOfInterest();
        $value1 = new PointOfInterest();
        $value2 = new PointOfInterest();

        $this->trip->addPointsOfInterest($value);
        $this->trip->addPointsOfInterest($value1);
        $this->trip->addPointsOfInterest($value2);

        self::assertCount(3, $this->trip->getPointsOfInterest());
        self::assertTrue($this->trip->getPointsOfInterest()->contains($value));
        self::assertTrue($this->trip->getPointsOfInterest()->contains($value1));
        self::assertTrue($this->trip->getPointsOfInterest()->contains($value2));

        $response = $this->trip->removePointsOfInterest($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getPointsOfInterest());
        self::assertFalse($this->trip->getPointsOfInterest()->contains($value));
        self::assertTrue($this->trip->getPointsOfInterest()->contains($value1));
        self::assertTrue($this->trip->getPointsOfInterest()->contains($value2));
    }

    public function testGetSteps(): void
    {
        $value = new Step();
        $value1 = new Step();
        $value2 = new Step();

        $this->trip->addStep($value);
        $this->trip->addStep($value1);
        $this->trip->addStep($value2);

        self::assertCount(3, $this->trip->getSteps());
        self::assertTrue($this->trip->getSteps()->contains($value));
        self::assertTrue($this->trip->getSteps()->contains($value1));
        self::assertTrue($this->trip->getSteps()->contains($value2));

        $response = $this->trip->removeStep($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getSteps());
        self::assertFalse($this->trip->getSteps()->contains($value));
        self::assertTrue($this->trip->getSteps()->contains($value1));
        self::assertTrue($this->trip->getSteps()->contains($value2));
    }

    public function testGetTravels(): void
    {
        $value = new Travel();
        $value1 = new Travel();
        $value2 = new Travel();

        $this->trip->addTravel($value);
        $this->trip->addTravel($value1);
        $this->trip->addTravel($value2);

        self::assertCount(3, $this->trip->getTravels());
        self::assertTrue($this->trip->getTravels()->contains($value));
        self::assertTrue($this->trip->getTravels()->contains($value1));
        self::assertTrue($this->trip->getTravels()->contains($value2));

        $response = $this->trip->removeTravel($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getTravels());
        self::assertFalse($this->trip->getTravels()->contains($value));
        self::assertTrue($this->trip->getTravels()->contains($value1));
        self::assertTrue($this->trip->getTravels()->contains($value2));
    }

    public function testGetTripUsers(): void
    {
        $value = new TripUser();
        $value1 = new TripUser();
        $value2 = new TripUser();

        $this->trip->addTripUser($value);
        $this->trip->addTripUser($value1);
        $this->trip->addTripUser($value2);

        self::assertCount(3, $this->trip->getTripUsers());
        self::assertTrue($this->trip->getTripUsers()->contains($value));
        self::assertTrue($this->trip->getTripUsers()->contains($value1));
        self::assertTrue($this->trip->getTripUsers()->contains($value2));

        $response = $this->trip->removeTripUser($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getTripUsers());
        self::assertFalse($this->trip->getTripUsers()->contains($value));
        self::assertTrue($this->trip->getTripUsers()->contains($value1));
        self::assertTrue($this->trip->getTripUsers()->contains($value2));
    }

    public function testAlbumElements(): void
    {
        $value = new LogBookEntry();
        $value1 = new LogBookEntry();
        $value2 = new Picture();

        $this->trip->addAlbumElement($value);
        $this->trip->addAlbumElement($value1);
        $this->trip->addAlbumElement($value2);

        self::assertCount(3, $this->trip->getAlbumElements());
        self::assertTrue($this->trip->getAlbumElements()->contains($value));
        self::assertTrue($this->trip->getAlbumElements()->contains($value1));
        self::assertTrue($this->trip->getAlbumElements()->contains($value2));

        $response = $this->trip->removeAlbumElement($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertCount(2, $this->trip->getAlbumElements());
        self::assertFalse($this->trip->getAlbumElements()->contains($value));
        self::assertTrue($this->trip->getAlbumElements()->contains($value1));
        self::assertTrue($this->trip->getAlbumElements()->contains($value2));
    }

    public function testGetLink() : void
    {
        $value = 'link';

        $response = $this->trip->setLink($value);

        self::assertInstanceOf(Trip::class, $response);
        self::assertEquals($value, $this->trip->getLink());

        $this->trip->generateLink();

        self::assertNotNull($this->trip->getLink());
        self::assertNotEquals($value, $this->trip->getLink());
    }
}
