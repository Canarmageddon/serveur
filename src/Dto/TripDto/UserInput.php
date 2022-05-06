<?php

namespace App\Dto\TripDto;

class UserInput {

    private ?string $email;

    private ?int $trip;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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