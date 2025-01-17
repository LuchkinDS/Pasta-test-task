<?php

namespace App\Tests\WebTests;

use App\Data\Entities\Paste;
use App\Domain\Entities\Exposure;
use App\Domain\Repositories\PasteRepositoryInterface;
use App\Tests\Unit\DummyHashGenerator;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PasteByHashWebTest extends WebTestCase
{
    private ?EntityManagerInterface $em = null;
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
    public function testShowPasteByHas(): void
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
        );
        $this->em->persist($paste);
        $this->em->flush();

        $this->client->request('GET', '/678809cc085e9');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div>div>div', 'test content 1');
    }

    public function test404PasteByHas(): void
    {
        static::bootKernel();

        $this->client->request('GET', '/678809cc085e9');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertSelectorTextContains('div>div>div', 'Not Found (#404)');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->em->close();
        $this->em = null;
    }
}