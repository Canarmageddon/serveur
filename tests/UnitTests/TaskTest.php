<?php

namespace App\Tests\UnitTests;

use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\Travel;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private Task $task;
    private DateTimeImmutable $creationDate;

    protected function setUp() : void
    {
        parent::setUp();

        $this->task = new Task();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetName() : void
    {
        $value = 'name';

        $response = $this->task->setName($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getName());
    }

    public function testGetDescription() : void
    {
        $value = 'description';

        $response = $this->task->setDescription($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getDescription());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->task->setCreator($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getCreator());
        self::assertInstanceOf(User::class, $this->task->getCreator());
    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->task->getCreationDate()->format('Y-m-d'));
    }

    public function testGetDate() : void
    {
        $value = new DateTime();

        $response = $this->task->setDate($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getDate());
    }

    public function testGetToDoList(): void
    {
        $value = new ToDoList();

        $response = $this->task->setToDoList($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getToDoList());
    }

    public function testGetIsDone() : void
    {
        $value = false;
        $value1 = true;

        $response = $this->task->setIsDone($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getIsDone());

        $response = $this->task->setIsDone($value1);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value1, $this->task->getIsDone());
    }

    public function testGetMapElement(): void
    {
        $value = new PointOfInterest();
        $value1 = new Step();
        $value2 = new Travel();

        $response = $this->task->setMapElement($value);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value, $this->task->getMapElement());
        self::assertInstanceOf(PointOfInterest::class, $this->task->getMapElement());

        $response = $this->task->setMapElement($value1);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value1, $this->task->getMapElement());
        self::assertInstanceOf(Step::class, $this->task->getMapElement());

        $response = $this->task->setMapElement($value2);

        self::assertInstanceOf(Task::class, $response);
        self::assertEquals($value2, $this->task->getMapElement());
        self::assertInstanceOf(Travel::class, $this->task->getMapElement());
    }
}
