<?php

namespace App\Dto;

class LogBookEntryInput
{
    private ?string $content = null;

    private ?int $trip = null;

    private ?int $creator = null;

    private ?int $album = null;

    private ?int $location = null;

    private ?float $latitude = null;

    private ?float $longitude = null;

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
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
     * @return int|null
     */
    public function getAlbum(): ?int
    {
        return $this->album;
    }

    /**
     * @param int|null $album
     */
    public function setAlbum(?int $album): void
    {
        $this->album = $album;
    }

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
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }
}