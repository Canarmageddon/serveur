<?php

namespace App\Tests\UnitTests;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\Trip;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{
    private ToDoList $toDoList;

    protected function setUp() : void
    {
        parent::setUp();

        $this->toDoList = new ToDoList();
    }

    public function testGetName() : void
    {
        $value = 'name';

        $response = $this->toDoList->setName($value);

        self::assertInstanceOf(ToDoList::class, $response);
        self::assertEquals($value, $this->toDoList->getName());
    }

    public function testGetTasks(): void
    {
        $value = new Task();
        $value1 = new Task();
        $value2 = new Task();

        $this->toDoList->addTask($value);
        $this->toDoList->addTask($value1);
        $this->toDoList->addTask($value2);

        self::assertCount(3, $this->toDoList->getTasks());
        self::assertTrue($this->toDoList->getTasks()->contains($value));
        self::assertTrue($this->toDoList->getTasks()->contains($value1));
        self::assertTrue($this->toDoList->getTasks()->contains($value2));

        $response = $this->toDoList->removeTask($value);

        self::assertInstanceOf(ToDoList::class, $response);
        self::assertCount(2, $this->toDoList->getTasks());
        self::assertFalse($this->toDoList->getTasks()->contains($value));
        self::assertTrue($this->toDoList->getTasks()->contains($value1));
        self::assertTrue($this->toDoList->getTasks()->contains($value2));
    }

    public function testGetTrip() : void
    {
        $value = new Trip();

        $response = $this->toDoList->setTrip($value);

        self::assertInstanceOf(ToDoList::class, $response);
        self::assertEquals($value, $this->toDoList->getTrip());
    }
}
