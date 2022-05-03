<?php

namespace App\Dto\TripDto;

class UserInput {

    private ?string $emailUser;

    private ?int $idTrip;

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setCreator(?string $emailUser): self
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    public function getIdTrip(): ?int
    {
        return $this->idTrip;
    }

    public function setIdTrip(?int $idTrip): self
    {
        $this->idTrip = $idTrip;

        return $this;
    }
}