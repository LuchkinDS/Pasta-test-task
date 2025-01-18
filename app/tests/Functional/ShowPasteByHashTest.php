<?php

namespace App\Tests\Functional;

use App\Data\Entities\Paste;
use App\Data\Mappers\MapperPaste;
use App\Domain\Entities\Exposure;
use App\Domain\Entities\PasteResponse;
use App\Domain\Entities\PastesResponse;
use App\Domain\Entities\SimpleHashGenerator;
use App\Domain\Exceptions\PasteNotFoundException;
use App\Domain\Repositories\PasteRepositoryInterface;
use App\Tests\Unit\DummyHashGenerator;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

class ShowPasteByHashTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private PasteRepositoryInterface $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $this->repository = $this->getContainer()->get(PasteRepositoryInterface::class);
    }

    public function testExpiredPaste(): void
    {
        // не должен быть в выборке (просрочен)
        $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
        $minuteInterval = new DateInterval('PT1M');
        $paste = new Paste(
            id: null,
            title: "test title 1",
            content: "test content 1",
            releaseDate: $currentDateTime,
            expirationDate: $currentDateTime->sub($minuteInterval),
            exposure: Exposure::Public,
            hash: (new DummyHashGenerator('678809cc085e9'))->getHash(),
            burn: false,
            read: 0
        );
        $this->entityManager->persist($paste);
        $this->entityManager->flush();

        $hasPaste = $this->repository->hasHash('678809cc085e9');
        assertTrue($hasPaste);

        $this->expectException(PasteNotFoundException::class);
        $this->repository->getPasteByHash('678809cc085e9');
    }

    public function testCorrectPastes(): void
    {
        $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
        $hourInterval = new DateInterval('PT1H');
        $paste = new Paste(
            id: null,
            title: "test title 1",
            content: "test content 1",
            releaseDate: $currentDateTime,
            expirationDate: $currentDateTime->add($hourInterval),
            exposure: Exposure::Public,
            hash: (new DummyHashGenerator('678809cc085e9'))->getHash(),
            burn: false,
            read: 0,
        );
        $this->entityManager->persist($paste);
        $this->entityManager->flush();

        $paste = $this->repository->getPasteByHash('678809cc085e9');

        assertInstanceOf(PasteResponse::class, $paste);
        assertEquals('678809cc085e9', $paste->hash);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}