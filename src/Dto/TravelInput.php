<?php

namespace App\Dto;

class TravelInput {

    private ?int $duration;

    private ?int $trip;

    private ?int $start;

    private ?int $end;

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     */
    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
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

    /**
     * @return int|null
     */
    public function getStart(): ?int
    {
        return $this->start;
    }

    /**
     * @param int|null $start
     */
    public function setStart(?int $start): void
    {
        $this->start = $start;
    }

    /**
     * @return int|null
     */
    public function getEnd(): ?int
    {
        return $this->end;
    }

    /**
     * @param int|null $end
     */
    public function setEnd(?int $end): void
    {
        $this->end = $end;
    }
}
