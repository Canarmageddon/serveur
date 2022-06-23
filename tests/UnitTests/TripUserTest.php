<?php

namespace App\Tests\UnitTests;

use App\Entity\Trip;
use App\Entity\TripUser;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TripUserTest extends TestCase
{
    private TripUser $tripUser;

    protected function setUp() : void
    {
        parent::setUp();

        $this->tripUser = new TripUser();
    }

    public function testGetCost() : void
    {
        $value = new Trip();

        $response = $this->tripUser->setTrip($value);

        self::assertInstanceOf(TripUser::class, $response);
        self::assertEquals($value, $this->tripUser->getTrip());
    }

    public function testGetUser() : void
    {
        $value = new User();

        $response = $this->tripUser->setUser($value);

        self::assertInstanceOf(TripUser::class, $response);
        self::assertEquals($value, $this->tripUser->getUser());
    }
}
