<?php

namespace App\Dto;

class PointOfInterestInput {

    private ?float $longitude = null;

    private ?float $latitude = null;

    private ?string $name = null;

    private ?string $type = null;

    private ?string $title = null;

    private ?string $description = null;

    private ?int $creator = null;

    private ?int $trip = null;

    private ?int $step = null;

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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(?int $step): self
    {
        $this->step = $step;

        return $this;
    }
}