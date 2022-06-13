<?php

namespace App\Dto;

class LogBookEntryInput
{
    private ?string $content = null;

    private ?int $trip = null;

    private ?int $creator = null;

    private ?int $album = null;

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
}