<?php

namespace App\Tests\UnitTests;

use App\Entity\Document;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\Trip;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PointOfInterestTest extends TestCase
{
    private PointOfInterest $pointOfInterest;
    private DateTimeImmutable $creationDate;

    public function setUp(): void
    {
        parent::setUp();

        $this->pointOfInterest = new PointOfInterest();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetType() : void
    {
        $value = 'point_of_interest';

        $response = $this->pointOfInterest->setType($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getType());
    }

    public function testGetDocuments(): void
    {
        $value = new Document();
        $value1 = new Document();
        $value2 = new Document();

        $this->pointOfInterest->addDocument($value);
        $this->pointOfInterest->addDocument($value1);
        $this->pointOfInterest->addDocument($value2);

        self::assertCount(3, $this->pointOfInterest->getDocuments());
        self::assertTrue($this->pointOfInterest->getDocuments()->contains($value));
        self::assertTrue($this->pointOfInterest->getDocuments()->contains($value1));
        self::assertTrue($this->pointOfInterest->getDocuments()->contains($value2));

        $response = $this->pointOfInterest->removeDocument($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertCount(2, $this->pointOfInterest->getDocuments());
        self::assertFalse($this->pointOfInterest->getDocuments()->contains($value));
        self::assertTrue($this->pointOfInterest->getDocuments()->contains($value1));
        self::assertTrue($this->pointOfInterest->getDocuments()->contains($value2));
    }

    public function testGetTasks(): void
    {
        $value = new Task();
        $value1 = new Task();
        $value2 = new Task();

        $this->pointOfInterest->addTask($value);
        $this->pointOfInterest->addTask($value1);
        $this->pointOfInterest->addTask($value2);

        self::assertCount(3, $this->pointOfInterest->getTasks());
        self::assertTrue($this->pointOfInterest->getTasks()->contains($value));
        self::assertTrue($this->pointOfInterest->getTasks()->contains($value1));
        self::assertTrue($this->pointOfInterest->getTasks()->contains($value2));

        $response = $this->pointOfInterest->removeTask($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertCount(2, $this->pointOfInterest->getTasks());
        self::assertFalse($this->pointOfInterest->getTasks()->contains($value));
        self::assertTrue($this->pointOfInterest->getTasks()->contains($value1));
        self::assertTrue($this->pointOfInterest->getTasks()->contains($value2));
    }

    /******** **/

    public function testGetLocation(): void
    {
        $value = new Location();

        $response = $this->pointOfInterest->setLocation($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getLocation());
        self::assertInstanceOf(Location::class, $this->pointOfInterest->getLocation());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->pointOfInterest->setCreator($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getCreator());
        self::assertInstanceOf(User::class, $this->pointOfInterest->getCreator());
    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->pointOfInterest->getCreationDate()->format('Y-m-d'));
    }

    public function testGetDescription() : void
    {
        $value = 'description';

        $response = $this->pointOfInterest->setDescription($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getDescription());
    }

    public function testGetStep() : void
    {
        $value = new Step();

        $response = $this->pointOfInterest->setStep($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getStep());
    }

    public function testGetTrip() : void
    {
        $value = new Trip();

        $response = $this->pointOfInterest->setTrip($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getTrip());
    }

    public function testGetTitle() : void
    {
        $value = 'title';

        $response = $this->pointOfInterest->setTitle($value);

        self::assertInstanceOf(PointOfInterest::class, $response);
        self::assertEquals($value, $this->pointOfInterest->getTitle());
    }
}
