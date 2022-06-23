<?php

namespace App\Tests\UnitTests;

use App\Entity\Document;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\Travel;
use App\Entity\Trip;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class StepTest extends TestCase
{
    private Step $step;
    private DateTimeImmutable $creationDate;

    public function setUp(): void
    {
        parent::setUp();

        $this->step = new Step();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetType() : void
    {
        $value = 'point_of_interest';

        $response = $this->step->setType($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getType());
    }

    public function testGetDocuments(): void
    {
        $value = new Document();
        $value1 = new Document();
        $value2 = new Document();

        $this->step->addDocument($value);
        $this->step->addDocument($value1);
        $this->step->addDocument($value2);

        self::assertCount(3, $this->step->getDocuments());
        self::assertTrue($this->step->getDocuments()->contains($value));
        self::assertTrue($this->step->getDocuments()->contains($value1));
        self::assertTrue($this->step->getDocuments()->contains($value2));

        $response = $this->step->removeDocument($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertCount(2, $this->step->getDocuments());
        self::assertFalse($this->step->getDocuments()->contains($value));
        self::assertTrue($this->step->getDocuments()->contains($value1));
        self::assertTrue($this->step->getDocuments()->contains($value2));
    }

    public function testGetTasks(): void
    {
        $value = new Task();
        $value1 = new Task();
        $value2 = new Task();

        $this->step->addTask($value);
        $this->step->addTask($value1);
        $this->step->addTask($value2);

        self::assertCount(3, $this->step->getTasks());
        self::assertTrue($this->step->getTasks()->contains($value));
        self::assertTrue($this->step->getTasks()->contains($value1));
        self::assertTrue($this->step->getTasks()->contains($value2));

        $response = $this->step->removeTask($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertCount(2, $this->step->getTasks());
        self::assertFalse($this->step->getTasks()->contains($value));
        self::assertTrue($this->step->getTasks()->contains($value1));
        self::assertTrue($this->step->getTasks()->contains($value2));
    }

    public function testGetLocation(): void
    {
        $value = new Location();

        $response = $this->step->setLocation($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getLocation());
        self::assertInstanceOf(Location::class, $this->step->getLocation());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->step->setCreator($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getCreator());
        self::assertInstanceOf(User::class, $this->step->getCreator());
    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->step->getCreationDate()->format('Y-m-d'));
    }

    public function testGetDescription() : void
    {
        $value = 'description';

        $response = $this->step->setDescription($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getDescription());
    }

    public function testGetPointOfInterest(): void
    {
        $value = new PointOfInterest();
        $value1 = new PointOfInterest();
        $value2 = new PointOfInterest();

        $this->step->addPointsOfInterest($value);
        $this->step->addPointsOfInterest($value1);
        $this->step->addPointsOfInterest($value2);

        self::assertCount(3, $this->step->getPointsOfInterest());
        self::assertTrue($this->step->getPointsOfInterest()->contains($value));
        self::assertTrue($this->step->getPointsOfInterest()->contains($value1));
        self::assertTrue($this->step->getPointsOfInterest()->contains($value2));

        $response = $this->step->removePointsOfInterest($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertCount(2, $this->step->getPointsOfInterest());
        self::assertFalse($this->step->getPointsOfInterest()->contains($value));
        self::assertTrue($this->step->getPointsOfInterest()->contains($value1));
        self::assertTrue($this->step->getPointsOfInterest()->contains($value2));
    }

    public function testGetTrip() : void
    {
        $value = new Trip();

        $response = $this->step->setTrip($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getTrip());
    }

    public function testGetStarts() : void
    {
        $value = new Travel();
        $value1 = new Travel();
        $value2 = new Travel();

        $this->step->addStart($value);
        $this->step->addStart($value1);
        $this->step->addStart($value2);

        self::assertCount(3, $this->step->getStarts());
        self::assertTrue($this->step->getStarts()->contains($value));
        self::assertTrue($this->step->getStarts()->contains($value1));
        self::assertTrue($this->step->getStarts()->contains($value2));

        $response = $this->step->removeStart($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertCount(2, $this->step->getStarts());
        self::assertFalse($this->step->getStarts()->contains($value));
        self::assertTrue($this->step->getStarts()->contains($value1));
        self::assertTrue($this->step->getStarts()->contains($value2));
    }

    public function testGetEnds() : void
    {
        $value = new Travel();
        $value1 = new Travel();
        $value2 = new Travel();

        $this->step->addEnd($value);
        $this->step->addEnd($value1);
        $this->step->addEnd($value2);

        self::assertCount(3, $this->step->getEnds());
        self::assertTrue($this->step->getEnds()->contains($value));
        self::assertTrue($this->step->getEnds()->contains($value1));
        self::assertTrue($this->step->getEnds()->contains($value2));

        $response = $this->step->removeEnd($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertCount(2, $this->step->getEnds());
        self::assertFalse($this->step->getEnds()->contains($value));
        self::assertTrue($this->step->getEnds()->contains($value1));
        self::assertTrue($this->step->getEnds()->contains($value2));
    }

    public function testGetTitle() : void
    {
        $value = 'title';

        $response = $this->step->setTitle($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getTitle());
    }

    public function testGetDate() : void
    {
        $value = new DateTime();

        $response = $this->step->setDate($value);

        self::assertInstanceOf(Step::class, $response);
        self::assertEquals($value, $this->step->getDate());
    }
}
