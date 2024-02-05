<?php

namespace App\DataFixtures;

use App\Repository\DeveloperRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeveloperFixtures extends Fixture
{
    private DeveloperRepository $developerRepository;
    public function __construct(DeveloperRepository $developerRepository)
    {
        $this->developerRepository = $developerRepository;
    }
    public function load(ObjectManager $manager): void
    {
        $developers = [
            ['developerName' => 'DEV1', 'duration' => 1, 'difficulty' => 1],
            ['developerName' => 'DEV2', 'duration' => 1, 'difficulty' => 2],
            ['developerName' => 'DEV3', 'duration' => 1, 'difficulty' => 3],
            ['developerName' => 'DEV4', 'duration' => 1, 'difficulty' => 4],
            ['developerName' => 'DEV5', 'duration' => 1, 'difficulty' => 5],
        ];

        foreach ($developers as $developer) {
            $this->developerRepository->saveDeveloper($developer);
        }

        $manager->flush();
    }
}
