<?php

namespace App\Tests\UnitTests;

use App\Entity\Location;
use App\Entity\Picture;
use App\Entity\Trip;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    private Picture $picture;
    private DateTimeImmutable $creationDate;

    public function setUp(): void
    {
        parent::setUp();

        $this->picture = new Picture();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetType() : void
    {
        $value = 'picture';

        $response = $this->picture->setType($value);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($value, $this->picture->getType());
    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->picture->getCreationDate()->format('Y-m-d'));
    }

    public function testGetTrip() : void
    {
        $trip = new Trip();

        $response = $this->picture->setTrip($trip);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($trip, $this->picture->getTrip());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->picture->setCreator($value);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($value, $this->picture->getCreator());
        self::assertInstanceOf(User::class, $this->picture->getCreator());
    }

    public function testGetLocation(): void
    {
        $value = new Location();

        $response = $this->picture->setLocation($value);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($value, $this->picture->getLocation());
        self::assertInstanceOf(Location::class, $this->picture->getLocation());
    }

    public function testGetType2() : void
    {
        $value = 'picture';

        $response = $this->picture->setType2($value);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($value, $this->picture->getType2());
    }

    public function testGetFilePath() : void
    {
        $value = 'content';

        $response = $this->picture->setFilePath($value);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($value, $this->picture->getFilePath());
    }
}
