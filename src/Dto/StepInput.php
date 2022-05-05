<?php

namespace App\Dto;

class StepInput
{
    private ?int $location;

    private ?int $creator;

    private ?string $description;

    private ?int $trip;

    /**
     * @return int|null
     */
    public function getLocation(): ?int
    {
        return $this->location;
    }

    /**
     * @param int|null $location
     */
    public function setLocation(?int $location): void
    {
        $this->location = $location;
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