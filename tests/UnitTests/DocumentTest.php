<?php

namespace App\Tests\UnitTests;

use App\Entity\Document;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Travel;
use App\Entity\User;
use PhpParser\Comment\Doc;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class DocumentTest extends TestCase
{
    private Document $document;

    public function setUp(): void
    {
        parent::setUp();

        $this->document = new Document();
    }

    public function testGetName() : void
    {
        $value = 'documentName';

        $response = $this->document->setName($value);

        self::assertInstanceOf(Document::class, $response);
        self::assertEquals($value, $this->document->getName());
    }

    public function testGetMapElement(): void
    {
        $value = new PointOfInterest();
        $value1 = new Step();
        $value2 = new Travel();

        $response = $this->document->setMapElement($value);

        self::assertInstanceOf(Document::class, $response);
        self::assertEquals($value, $this->document->getMapElement());
        self::assertInstanceOf(PointOfInterest::class, $this->document->getMapElement());

        $response = $this->document->setMapElement($value1);

        self::assertInstanceOf(Document::class, $response);
        self::assertEquals($value1, $this->document->getMapElement());
        self::assertInstanceOf(Step::class, $this->document->getMapElement());

        $response = $this->document->setMapElement($value2);

        self::assertInstanceOf(Document::class, $response);
        self::assertEquals($value2, $this->document->getMapElement());
        self::assertInstanceOf(Travel::class, $this->document->getMapElement());
    }

    public function testGetFilePath() : void
    {
        $value = '/document.pdf';

        $response = $this->document->setFilePath($value);

        self::assertInstanceOf(Document::class, $response);
        self::assertEquals($value, $this->document->getFilePath());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->document->setCreator($value);

        self::assertInstanceOf(Document::class, $response);
        self::assertEquals($value, $this->document->getCreator());
        self::assertInstanceOf(User::class, $this->document->getCreator());
    }
}
