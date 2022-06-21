<?php

namespace App\Dto\TripDto;

class TripInput {

    private ?string $name = null;

    private ?int $creator = null;

    private ?bool $isEnded = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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
     * @return bool|null
     */
    public function getIsEnded(): ?bool
    {
        return $this->isEnded;
    }

    /**
     * @param bool|null $isEnded
     */
    public function setIsEnded(?bool $isEnded): void
    {
        $this->isEnded = $isEnded;
    }
}