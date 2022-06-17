<?php

namespace App\Tests\UnitTests;

use App\Entity\Document;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\Travel;
use App\Entity\Trip;
use PHPUnit\Framework\TestCase;

class TravelTest extends TestCase
{
    private Travel $travel;

    public function setUp(): void
    {
        parent::setUp();

        $this->travel = new Travel();
    }

    public function testGetType() : void
    {
        $value = 'point_of_interest';

        $response = $this->travel->setType($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertEquals($value, $this->travel->getType());
    }

    public function testGetDocuments(): void
    {
        $value = new Document();
        $value1 = new Document();
        $value2 = new Document();

        $this->travel->addDocument($value);
        $this->travel->addDocument($value1);
        $this->travel->addDocument($value2);

        self::assertCount(3, $this->travel->getDocuments());
        self::assertTrue($this->travel->getDocuments()->contains($value));
        self::assertTrue($this->travel->getDocuments()->contains($value1));
        self::assertTrue($this->travel->getDocuments()->contains($value2));

        $response = $this->travel->removeDocument($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertCount(2, $this->travel->getDocuments());
        self::assertFalse($this->travel->getDocuments()->contains($value));
        self::assertTrue($this->travel->getDocuments()->contains($value1));
        self::assertTrue($this->travel->getDocuments()->contains($value2));
    }

    public function testGetTasks(): void
    {
        $value = new Task();
        $value1 = new Task();
        $value2 = new Task();

        $this->travel->addTask($value);
        $this->travel->addTask($value1);
        $this->travel->addTask($value2);

        self::assertCount(3, $this->travel->getTasks());
        self::assertTrue($this->travel->getTasks()->contains($value));
        self::assertTrue($this->travel->getTasks()->contains($value1));
        self::assertTrue($this->travel->getTasks()->contains($value2));

        $response = $this->travel->removeTask($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertCount(2, $this->travel->getTasks());
        self::assertFalse($this->travel->getTasks()->contains($value));
        self::assertTrue($this->travel->getTasks()->contains($value1));
        self::assertTrue($this->travel->getTasks()->contains($value2));
    }

    public function testGetDuration() : void
    {
        $value = 3600;

        $response = $this->travel->setDuration($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertEquals($value, $this->travel->getDuration());
    }

    public function testGetTrip() : void
    {
        $value = new Trip();

        $response = $this->travel->setTrip($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertEquals($value, $this->travel->getTrip());
    }

    public function testGetStart() : void
    {
        $value = new Step();

        $response = $this->travel->setStart($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertEquals($value, $this->travel->getStart());
    }

    public function testGetEnd() : void
    {
        $value = new Step();

        $response = $this->travel->setEnd($value);

        self::assertInstanceOf(Travel::class, $response);
        self::assertEquals($value, $this->travel->getEnd());
    }
}
