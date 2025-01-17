<?php

namespace App\Tests\Functional;

use App\Data\Entities\Paste;
use App\Data\Mappers\MapperPaste;
use App\Domain\Entities\Exposure;
use App\Domain\Entities\Pager;
use App\Domain\Entities\PastesResponse;
use App\Domain\Entities\SimpleHashGenerator;
use App\Domain\Repositories\PasteRepositoryInterface;
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

class ShowPublicPasteTest extends KernelTestCase
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

    public function testEmptyPublicPastes(): void
    {
        foreach (range(1, 10) as $i) {
            $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
            $minuteInterval = new DateInterval('PT1M');
            $paste = new Paste(
                id: null,
                title: "test title {$i}",
                content: "test content {$i}",
                releaseDate: $currentDateTime,
                expirationDate: $currentDateTime->sub($minuteInterval),
                exposure: Exposure::Public,
                hash: (new SimpleHashGenerator())->getHash(),
            );
            $this->entityManager->persist($paste);
        }
        $this->entityManager->flush();

        $pastes = $this->repository->getPublicPaste(new Pager());

        assertNotEmpty($pastes);
        assertInstanceOf(PastesResponse::class, $pastes);
        assertIsArray($pastes->items);
        assertEmpty($pastes->items);
    }

    public function testNotEmptyPublicPastes(): void
    {
        foreach (range(1, 20) as $i) {
            $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
            $hourInterval = new DateInterval('P1D');
            $paste = new Paste(
                id: null,
                title: "test title {$i}",
                content: "test content {$i}",
                releaseDate: $currentDateTime,
                expirationDate: $currentDateTime->add($hourInterval),
                exposure: Exposure::Public,
                hash: (new SimpleHashGenerator())->getHash(),
            );
            $this->entityManager->persist($paste);
        }
        $this->entityManager->flush();

        $pastes = $this->repository->getPublicPaste(new Pager());

        assertNotEmpty($pastes);
        assertInstanceOf(PastesResponse::class, $pastes);
        assertIsArray($pastes->items);
        assertNotEmpty($pastes->items);
        assertEquals(10, count($pastes->items));
    }

    public function testPublicPastes(): void
    {
        // должен быть в выборке
        $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
        $hourInterval = new DateInterval('P1D');
        $paste = new Paste(
            id: null,
            title: "test title 1",
            content: "test content 1",
            releaseDate: $currentDateTime,
            expirationDate: $currentDateTime->add($hourInterval),
            exposure: Exposure::Public,
            hash: (new SimpleHashGenerator())->getHash(),
        );
        $this->entityManager->persist($paste);

        // не должен быть в выборке (просрочен)
        $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
        $minuteInterval = new DateInterval('PT1M');
        $paste = new Paste(
            id: null,
            title: "test title 2",
            content: "test content 2",
            releaseDate: $currentDateTime,
            expirationDate: $currentDateTime->sub($minuteInterval),
            exposure: Exposure::Public,
            hash: (new SimpleHashGenerator())->getHash(),
        );
        $this->entityManager->persist($paste);

        // не должен быть в выборке (unlisted)
        $currentDateTime = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
        $hourInterval = new DateInterval('P1D');
        $paste = new Paste(
            id: null,
            title: "test title 2",
            content: "test content 2",
            releaseDate: $currentDateTime,
            expirationDate: $currentDateTime->add($hourInterval),
            exposure: Exposure::Unlisted,
            hash: (new SimpleHashGenerator())->getHash(),
        );
        $this->entityManager->persist($paste);

        $this->entityManager->flush();

        $pastes = $this->repository->getPublicPaste(new Pager());

        assertNotEmpty($pastes);
        assertInstanceOf(PastesResponse::class, $pastes);
        assertIsArray($pastes->items);
        assertNotEmpty($pastes->items);
        assertEquals(1, count($pastes->items));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}