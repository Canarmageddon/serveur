<?php

namespace App\Dto;

class PointOfInterestInput {

    private ?float $longitude = null;

    private ?float $latitude = null;

    private ?string $title = null;

    private ?string $description = null;

    private ?int $creator = null;

    private ?int $trip = null;

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreator(): ?int
    {
        return $this->creator;
    }

    public function setCreator(?int $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getTrip(): ?int
    {
        return $this->trip;
    }

    public function setTrip(?int $trip): self
    {
        $this->trip = $trip;

        return $this;
    }
}