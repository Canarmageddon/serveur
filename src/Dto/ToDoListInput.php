<?php

namespace App\Dto;

class ToDoListInput {

    private ?string $name = null;

    private ?int $trip = null;

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
     * @return int|null
     */
    public function getTrip(): ?int
    {
        return $this->trip;
    }

    /**
     * @param int|null $trip
     */
    public function setTrip(?int $trip): void
    {
        $this->trip = $trip;
    }
}