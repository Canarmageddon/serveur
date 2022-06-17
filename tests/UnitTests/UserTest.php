<?php

namespace App\Tests\UnitTests;

use App\Entity\User;
use App\Entity\PointOfInterest;
use App\Entity\Trip;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase{
    private User $user;
    private \DateTime $creationDate;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = new User();
        $this->creationDate = new \DateTime();
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

    //TODO
    public function getPointOfInterest(): void
    {
        self::assertCount(0, $this->user->getPointOfInterests());


        $response = $this->user->addPointOfInterest();
    }
}

?>