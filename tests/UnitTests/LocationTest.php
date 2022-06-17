<?php

namespace App\Tests\UnitTests;

use App\Entity\Location;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Trip;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    private Location $location;

    protected function setUp() : void
    {
        parent::setUp();

        $this->location = new Location();
    }

    public function testGetLatitude() : void
    {
        $value = 1.234;

        $response = $this->location->setLatitude($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertEquals($value, $this->location->getLatitude());
    }

    public function testGetLongitude() : void
    {
        $value = 1.234;

        $response = $this->location->setLongitude($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertEquals($value, $this->location->getLongitude());
    }

    public function testGetName() : void
    {
        $value = "name";

        $response = $this->location->setName($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertEquals($value, $this->location->getName());
    }

    public function testGetType() : void
    {
        $value = "type";

        $response = $this->location->setType($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertEquals($value, $this->location->getType());
    }

    public function testGetPointOfInterests(): void
    {
        $value = new PointOfInterest();
        $value1 = new PointOfInterest();
        $value2 = new PointOfInterest();

        $this->location->addPointOfInterest($value);
        $this->location->addPointOfInterest($value1);
        $this->location->addPointOfInterest($value2);

        self::assertCount(3, $this->location->getPointOfInterests());
        self::assertTrue($this->location->getPointOfInterests()->contains($value));
        self::assertTrue($this->location->getPointOfInterests()->contains($value1));
        self::assertTrue($this->location->getPointOfInterests()->contains($value2));

        $response = $this->location->removePointOfInterest($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertCount(2, $this->location->getPointOfInterests());
        self::assertFalse($this->location->getPointOfInterests()->contains($value));
        self::assertTrue($this->location->getPointOfInterests()->contains($value1));
        self::assertTrue($this->location->getPointOfInterests()->contains($value2));
    }

    public function testGetSteps(): void
    {
        $value = new Step();
        $value1 = new Step();
        $value2 = new Step();

        $this->location->addStep($value);
        $this->location->addStep($value1);
        $this->location->addStep($value2);

        self::assertCount(3, $this->location->getSteps());
        self::assertTrue($this->location->getSteps()->contains($value));
        self::assertTrue($this->location->getSteps()->contains($value1));
        self::assertTrue($this->location->getSteps()->contains($value2));

        $response = $this->location->removeStep($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertCount(2, $this->location->getSteps());
        self::assertFalse($this->location->getSteps()->contains($value));
        self::assertTrue($this->location->getSteps()->contains($value1));
        self::assertTrue($this->location->getSteps()->contains($value2));
    }

    public function testAlbumElements(): void
    {
        $trip = new Trip();
        $value = new LogBookEntry();
        $value1 = new LogBookEntry();
        $value2 = new Picture();

        $this->location->addAlbumElement($value);
        $this->location->addAlbumElement($value1);
        $this->location->addAlbumElement($value2);

        $trip->addAlbumElement($value);
        $trip->addAlbumElement($value1);
        $trip->addAlbumElement($value2);

        self::assertCount(3, $this->location->getAlbumElements());
        self::assertTrue($this->location->getAlbumElements()->contains($value));
        self::assertTrue($this->location->getAlbumElements()->contains($value1));
        self::assertTrue($this->location->getAlbumElements()->contains($value2));

        $response = $this->location->removeAlbumElement($value);

        self::assertInstanceOf(Location::class, $response);
        self::assertCount(2, $this->location->getAlbumElements());
        self::assertFalse($this->location->getAlbumElements()->contains($value));
        self::assertTrue($this->location->getAlbumElements()->contains($value1));
        self::assertTrue($this->location->getAlbumElements()->contains($value2));
    }
}
