<?php

namespace App\Tests\UnitTests;

use App\Entity\AlbumElement;
use App\Entity\Cost;
use App\Entity\CostUser;
use App\Entity\Document;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use App\Entity\Step;
use App\Entity\Task;
use App\Entity\TripUser;
use App\Entity\User;
use App\Entity\PointOfInterest;
use App\Entity\Trip;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase{
    private User $user;
    private DateTimeImmutable $creationDate;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = new User();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetEmail() : void
    {
        $value = 'test@test.fr';

        $response = $this->user->setEmail($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getEmail());
        self::assertEquals($value, $this->user->getUserIdentifier());
    }

    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];

        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());
        self::assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testGetPassword(): void
    {
        $value = 'password';

        $response = $this->user->setPassword($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getPassword());

    }

    public function testGetPlainPassword(): void
    {
        $value = 'password';

        $this->user->setPlainPassword($value);

        self::assertEquals($value, $this->user->getPlainPassword());

        $this->user->eraseCredentials();

        self::assertNull($this->user->getPlainPassword());
    }

    public function testGetFirstName(): void
    {
        $value = 'firstName';

        $response = $this->user->setFirstName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getFirstName());

    }

    public function testGetLastName(): void
    {
        $value = 'lastName';

        $response = $this->user->setLastName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getLastName());

    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->user->getCreationDate()->format('Y-m-d'));
    }

    public function testGetPointOfInterests(): void
    {
        $value = new PointOfInterest();
        $value1 = new PointOfInterest();
        $value2 = new PointOfInterest();

        $this->user->addPointOfInterest($value);
        $this->user->addPointOfInterest($value1);
        $this->user->addPointOfInterest($value2);

        self::assertCount(3, $this->user->getPointOfInterests());
        self::assertTrue($this->user->getPointOfInterests()->contains($value));
        self::assertTrue($this->user->getPointOfInterests()->contains($value1));
        self::assertTrue($this->user->getPointOfInterests()->contains($value2));

        $response = $this->user->removePointOfInterest($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getPointOfInterests());
        self::assertFalse($this->user->getPointOfInterests()->contains($value));
        self::assertTrue($this->user->getPointOfInterests()->contains($value1));
        self::assertTrue($this->user->getPointOfInterests()->contains($value2));
    }

    public function testGetSteps(): void
    {
        $value = new Step();
        $value1 = new Step();
        $value2 = new Step();

        $this->user->addStep($value);
        $this->user->addStep($value1);
        $this->user->addStep($value2);

        self::assertCount(3, $this->user->getSteps());
        self::assertTrue($this->user->getSteps()->contains($value));
        self::assertTrue($this->user->getSteps()->contains($value1));
        self::assertTrue($this->user->getSteps()->contains($value2));

        $response = $this->user->removeStep($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getSteps());
        self::assertFalse($this->user->getSteps()->contains($value));
        self::assertTrue($this->user->getSteps()->contains($value1));
        self::assertTrue($this->user->getSteps()->contains($value2));
    }

    public function testGetAlbumElements(): void
    {
        $trip = new Trip();
        $value = new LogBookEntry();
        $value1 = new LogBookEntry();
        $value2 = new Picture();

        $this->user->addAlbumElement($value);
        $this->user->addAlbumElement($value1);
        $this->user->addAlbumElement($value2);

        $trip->addAlbumElement($value);
        $trip->addAlbumElement($value1);
        $trip->addAlbumElement($value2);

        self::assertCount(3, $this->user->getAlbumElements());
        self::assertTrue($this->user->getAlbumElements()->contains($value));
        self::assertTrue($this->user->getAlbumElements()->contains($value1));
        self::assertTrue($this->user->getAlbumElements()->contains($value2));

        $response = $this->user->removeAlbumElement($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getAlbumElements());
        self::assertFalse($this->user->getAlbumElements()->contains($value));
        self::assertTrue($this->user->getAlbumElements()->contains($value1));
        self::assertTrue($this->user->getAlbumElements()->contains($value2));
    }

    public function testGetDocuments(): void
    {
        $value = new Document();
        $value1 = new Document();
        $value2 = new Document();

        $this->user->addDocument($value);
        $this->user->addDocument($value1);
        $this->user->addDocument($value2);

        self::assertCount(3, $this->user->getDocuments());
        self::assertTrue($this->user->getDocuments()->contains($value));
        self::assertTrue($this->user->getDocuments()->contains($value1));
        self::assertTrue($this->user->getDocuments()->contains($value2));

        $response = $this->user->removeDocument($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getDocuments());
        self::assertFalse($this->user->getDocuments()->contains($value));
        self::assertTrue($this->user->getDocuments()->contains($value1));
        self::assertTrue($this->user->getDocuments()->contains($value2));
    }

    public function testGetTasks(): void
    {
        $value = new Task();
        $value1 = new Task();
        $value2 = new Task();

        $this->user->addTask($value);
        $this->user->addTask($value1);
        $this->user->addTask($value2);

        self::assertCount(3, $this->user->getTasks());
        self::assertTrue($this->user->getTasks()->contains($value));
        self::assertTrue($this->user->getTasks()->contains($value1));
        self::assertTrue($this->user->getTasks()->contains($value2));

        $response = $this->user->removeTask($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getTasks());
        self::assertFalse($this->user->getTasks()->contains($value));
        self::assertTrue($this->user->getTasks()->contains($value1));
        self::assertTrue($this->user->getTasks()->contains($value2));
    }

    public function testGetCosts(): void
    {
        $value = new Cost();
        $value1 = new Cost();
        $value2 = new Cost();

        $this->user->addCost($value);
        $this->user->addCost($value1);
        $this->user->addCost($value2);

        self::assertCount(3, $this->user->getCosts());
        self::assertTrue($this->user->getCosts()->contains($value));
        self::assertTrue($this->user->getCosts()->contains($value1));
        self::assertTrue($this->user->getCosts()->contains($value2));

        $response = $this->user->removeCost($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getCosts());
        self::assertFalse($this->user->getCosts()->contains($value));
        self::assertTrue($this->user->getCosts()->contains($value1));
        self::assertTrue($this->user->getCosts()->contains($value2));
    }

    public function testGetTripUsers(): void
    {
        $value = new TripUser();
        $value1 = new TripUser();
        $value2 = new TripUser();

        $this->user->addTripUser($value);
        $this->user->addTripUser($value1);
        $this->user->addTripUser($value2);

        self::assertCount(3, $this->user->getTripUsers());
        self::assertTrue($this->user->getTripUsers()->contains($value));
        self::assertTrue($this->user->getTripUsers()->contains($value1));
        self::assertTrue($this->user->getTripUsers()->contains($value2));

        $response = $this->user->removeTripUser($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getTripUsers());
        self::assertFalse($this->user->getTripUsers()->contains($value));
        self::assertTrue($this->user->getTripUsers()->contains($value1));
        self::assertTrue($this->user->getTripUsers()->contains($value2));
    }

    public function testGetCostUsers(): void
    {
        $value = new CostUser();
        $value1 = new CostUser();
        $value2 = new CostUser();

        $this->user->addCostUser($value);
        $this->user->addCostUser($value1);
        $this->user->addCostUser($value2);

        self::assertCount(3, $this->user->getCostUsers());
        self::assertTrue($this->user->getCostUsers()->contains($value));
        self::assertTrue($this->user->getCostUsers()->contains($value1));
        self::assertTrue($this->user->getCostUsers()->contains($value2));

        $response = $this->user->removeCostUser($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getCostUsers());
        self::assertFalse($this->user->getCostUsers()->contains($value));
        self::assertTrue($this->user->getCostUsers()->contains($value1));
        self::assertTrue($this->user->getCostUsers()->contains($value2));
    }
}
