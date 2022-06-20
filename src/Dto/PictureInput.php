<?php

namespace App\Dto;

class PictureInput
{
    private ?int $album = null;

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