<?php

namespace App\Dto;

use DateTimeInterface;

class TaskInput
{
    private ?string $name = null;

    private ?string $description = null;

    private ?int $creator = null;

    private ?DateTimeInterface $date = null;

    private ?int $toDoList = null;

    private ?bool $isDone = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getCreator(): ?int
    {
        return $this->creator;
    }

    /**
     * @param int|null $creator
     */
    public function setCreator(?int $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface|null $date
     */
    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int|null
     */
    public function getToDoList(): ?int
    {
        return $this->toDoList;
    }

    /**
     * @param int|null $toDoList
     */
    public function setToDoList(?int $toDoList): void
    {
        $this->toDoList = $toDoList;
    }

    /**
     * @return bool|null
     */
    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * @param bool|null $isDone
     */
    public function setIsDone(?bool $isDone): void
    {
        $this->isDone = $isDone;
    }
}