<?php

namespace App\Tests\UnitTests;

use App\Entity\Album;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use App\Entity\Trip;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AlbumTest extends TestCase
{
    private Album $album;

    protected function setUp() : void
    {
        parent::setUp();

        $this->album = new Album();
    }

    public function testGetTrip() : void
    {
        $trip = new Trip();

        $response = $this->album->setTrip($trip);

        self::assertInstanceOf(Album::class, $response);
        self::assertEquals($trip, $this->album->getTrip());
    }

    public function testGetAlbumElements(): void
    {
        $value = new LogBookEntry();
        $value1 = new LogBookEntry();
        $value2 = new Picture();

        $this->album->addAlbumElement($value);
        $this->album->addAlbumElement($value1);
        $this->album->addAlbumElement($value2);

        self::assertCount(3, $this->album->getAlbumElements());
        self::assertTrue($this->album->getAlbumElements()->contains($value));
        self::assertTrue($this->album->getAlbumElements()->contains($value1));
        self::assertTrue($this->album->getAlbumElements()->contains($value2));

        $response = $this->album->removeAlbumElement($value);

        self::assertInstanceOf(Album::class, $response);
        self::assertCount(2, $this->album->getAlbumElements());
        self::assertFalse($this->album->getAlbumElements()->contains($value));
        self::assertTrue($this->album->getAlbumElements()->contains($value1));
        self::assertTrue($this->album->getAlbumElements()->contains($value2));
    }
}

