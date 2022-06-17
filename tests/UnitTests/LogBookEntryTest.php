<?php

namespace App\Tests\UnitTests;

use App\Entity\Document;
use App\Entity\Location;
use App\Entity\LogBookEntry;
use App\Entity\Trip;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class LogBookEntryTest extends TestCase
{
    private LogBookEntry $logBookEntry;
    private DateTimeImmutable $creationDate;

    public function setUp(): void
    {
        parent::setUp();

        $this->logBookEntry = new LogBookEntry();
        $this->creationDate = new DateTimeImmutable();
    }

    public function testGetType() : void
    {
        $value = 'log_book_entry';

        $response = $this->logBookEntry->setType($value);

        self::assertInstanceOf(LogBookEntry::class, $response);
        self::assertEquals($value, $this->logBookEntry->getType());
    }

    public function testGetCreationDate(): void
    {
        self::assertEquals($this->creationDate->format('Y-m-d'), $this->logBookEntry->getCreationDate()->format('Y-m-d'));
    }

    public function testGetTrip() : void
    {
        $value = new Trip();

        $response = $this->logBookEntry->setTrip($value);

        self::assertInstanceOf(LogBookEntry::class, $response);
        self::assertEquals($value, $this->logBookEntry->getTrip());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->logBookEntry->setCreator($value);

        self::assertInstanceOf(LogBookEntry::class, $response);
        self::assertEquals($value, $this->logBookEntry->getCreator());
        self::assertInstanceOf(User::class, $this->logBookEntry->getCreator());
    }

    public function testGetLocation(): void
    {
        $value = new Location();

        $response = $this->logBookEntry->setLocation($value);

        self::assertInstanceOf(LogBookEntry::class, $response);
        self::assertEquals($value, $this->logBookEntry->getLocation());
        self::assertInstanceOf(Location::class, $this->logBookEntry->getLocation());
    }

    public function testGetType2() : void
    {
        $value = 'log_book_entry';

        $response = $this->logBookEntry->setType2($value);

        self::assertInstanceOf(LogBookEntry::class, $response);
        self::assertEquals($value, $this->logBookEntry->getType2());
    }

    public function testGetContent() : void
    {
        $value = 'content';

        $response = $this->logBookEntry->setContent($value);

        self::assertInstanceOf(LogBookEntry::class, $response);
        self::assertEquals($value, $this->logBookEntry->getContent());
    }
}
