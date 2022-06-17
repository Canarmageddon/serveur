<?php

namespace App\Tests\UnitTests;

use App\Entity\Cost;
use App\Entity\CostUser;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CostUserTest extends TestCase
{
    private CostUser $costUser;

    protected function setUp() : void
    {
        parent::setUp();

        $this->costUser = new CostUser();
    }

    public function testGetCost() : void
    {
        $cost = new Cost();

        $response = $this->costUser->setCost($cost);

        self::assertInstanceOf(CostUser::class, $response);
        self::assertEquals($cost, $this->costUser->getCost());
    }

    public function testGetUser() : void
    {
        $user = new User();

        $response = $this->costUser->setUser($user);

        self::assertInstanceOf(CostUser::class, $response);
        self::assertEquals($user, $this->costUser->getUser());
    }
}
