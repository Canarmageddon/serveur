<?php

namespace App\Tests\UnitTests;

use App\Entity\Cost;
use App\Entity\CostUser;
use App\Entity\Trip;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CostTest extends TestCase
{
    private Cost $cost;
    private DateTimeImmutable $creationDate;

    protected function setUp() : void
    {
        parent::setUp();

        $this->cost = new Cost();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->cost->setCreator($value);

        self::assertInstanceOf(Cost::class, $response);
        self::assertEquals($value, $this->cost->getCreator());
        self::assertInstanceOf(User::class, $this->cost->getCreator());
    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->cost->getCreationDate()->format('Y-m-d'));
    }

    public function testGetCategory() : void
    {
        $value = 'category';

        $response = $this->cost->setCategory($value);

        self::assertInstanceOf(Cost::class, $response);
        self::assertEquals($value, $this->cost->getCategory());
    }

    public function testGetTrip() : void
    {
        $value = new Trip();

        $response = $this->cost->setTrip($value);

        self::assertInstanceOf(Cost::class, $response);
        self::assertEquals($value, $this->cost->getTrip());
    }

    public function testGetLabel() : void
    {
        $value = 'label';

        $response = $this->cost->setLabel($value);

        self::assertInstanceOf(Cost::class, $response);
        self::assertEquals($value, $this->cost->getLabel());
    }

    public function testGetValue() : void
    {
        $value = 1.234;

        $response = $this->cost->setValue($value);

        self::assertInstanceOf(Cost::class, $response);
        self::assertEquals($value, $this->cost->getValue());
    }

    public function testGetCostUsers(): void
    {
        $value = new CostUser();
        $value1 = new CostUser();
        $value2 = new CostUser();

        $this->cost->addCostUser($value);
        $this->cost->addCostUser($value1);
        $this->cost->addCostUser($value2);

        self::assertCount(3, $this->cost->getCostUsers());
        self::assertTrue($this->cost->getCostUsers()->contains($value));
        self::assertTrue($this->cost->getCostUsers()->contains($value1));
        self::assertTrue($this->cost->getCostUsers()->contains($value2));

        $response = $this->cost->removeCostUser($value);

        self::assertInstanceOf(Cost::class, $response);
        self::assertCount(2, $this->cost->getCostUsers());
        self::assertFalse($this->cost->getCostUsers()->contains($value));
        self::assertTrue($this->cost->getCostUsers()->contains($value1));
        self::assertTrue($this->cost->getCostUsers()->contains($value2));
    }
}
