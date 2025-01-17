<?php

namespace App\DataFixtures;

use App\Data\Repositories\PasteRepository;
use App\Domain\Entities\Expiration;
use App\Domain\Entities\Exposure;
use App\Domain\Entities\Paste;
use App\Domain\Entities\SimpleHashGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly PasteRepository $repository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (range(1, 1_00) as $i) {
            $paste = Paste::new(
                title: ["title $i", null][rand(0,1)],
                content: "content $i",
                expiration: Expiration::cases()[rand(0, 4)],
                exposure: Exposure::cases()[rand(0, 1)],
                generator: new SimpleHashGenerator()
            );
            $this->repository->create($paste);
        }
    }
}
