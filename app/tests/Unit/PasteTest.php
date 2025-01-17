<?php

namespace App\Tests\Unit;

use App\Domain\Entities\Expiration;
use App\Domain\Entities\Exposure;
use App\Domain\Entities\Paste;
use DateInterval;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

class PasteTest extends TestCase
{
    public function testCreatePublicPaste()
    {
        $hash = '6787a48965ecd';
        $paste = Paste::new(
            title: 'title',
            content: 'content',
            expiration: Expiration::Newer,
            exposure: Exposure::Public,
            generator: new DummyHashGenerator($hash),
        );
        assertNotEmpty($paste);
        assertEquals($paste->getHash(), $hash);
        assertEquals($paste->exposure, Exposure::Public);
    }

    public function testCreateUnlistedPaste()
    {
        $hash = '6787a48965ecd';
        $paste = Paste::new(
            title: 'title',
            content: 'content',
            expiration: Expiration::Newer,
            exposure: Exposure::Unlisted,
            generator: new DummyHashGenerator($hash),
        );
        assertNotEmpty($paste);
        assertEquals($paste->getHash(), $hash);
        assertEquals($paste->exposure, Exposure::Unlisted);
    }

    public function testCreatePasteExpirationHour()
    {
        $hash = '6787a48965ecd';
        $interval = new DateInterval('PT1H');
        $paste = Paste::new(
            title: 'title',
            content: 'content',
            expiration: Expiration::Hour,
            exposure: Exposure::Public,
            generator: new DummyHashGenerator($hash),
        );
        assertTrue($paste->getExpirationDate() == $paste->getReleaseDate()->add($interval));
    }

    public function testCreatePasteExpirationDay()
    {
        $hash = '6787a48965ecd';
        $interval = new DateInterval('P1D');
        $paste = Paste::new(
            title: 'title',
            content: 'content',
            expiration: Expiration::Day,
            exposure: Exposure::Public,
            generator: new DummyHashGenerator($hash),
        );
        assertTrue($paste->getExpirationDate() == $paste->getReleaseDate()->add($interval));
    }

    public function testCreatePasteExpirationWeek()
    {
        $hash = '6787a48965ecd';
        $interval = new DateInterval('P1W');
        $paste = Paste::new(
            title: 'title',
            content: 'content',
            expiration: Expiration::Week,
            exposure: Exposure::Public,
            generator: new DummyHashGenerator($hash),
        );
        assertTrue($paste->getExpirationDate() == $paste->getReleaseDate()->add($interval));
    }

    public function testCreatePasteExpirationMonths()
    {
        $hash = '6787a48965ecd';
        $interval = new DateInterval('P1M');
        $paste = Paste::new(
            title: 'title',
            content: 'content',
            expiration: Expiration::Month,
            exposure: Exposure::Public,
            generator: new DummyHashGenerator($hash),
        );
        assertTrue($paste->getExpirationDate() == $paste->getReleaseDate()->add($interval));
    }
}