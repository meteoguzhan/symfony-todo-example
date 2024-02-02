<?php
namespace App\Repository;

use App\Entity\Developer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developer::class);
    }

    public function getAllDevelopers(): array
    {
        return $this->findAll();
    }

    public function saveDeveloper(array $developerData): void
    {
        $developerEntity = new Developer();
        $developerEntity->setDeveloperName($developerData['developerName']);
        $developerEntity->setDuration($developerData['duration']);
        $developerEntity->setDifficulty($developerData['difficulty']);

        $this->_em->persist($developerEntity);
        $this->_em->flush();
    }
}
