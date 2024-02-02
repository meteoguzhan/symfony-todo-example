<?php
namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getAllTasks(): array
    {
        return $this->findAll();
    }

    public function saveTask(array $taskData): void
    {
        $taskEntity = new Task();
        $taskEntity->setTaskName($taskData['taskName']);
        $taskEntity->setDifficulty($taskData['difficulty']);
        $taskEntity->setDuration($taskData['duration']);

        $this->_em->persist($taskEntity);
        $this->_em->flush();
    }
}
