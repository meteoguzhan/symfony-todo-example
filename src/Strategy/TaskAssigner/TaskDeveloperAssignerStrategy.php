<?php
namespace App\Strategy\TaskAssigner;

use App\Entity\Developer;
use App\Interfaces\TaskAssigner\TaskAssignerStrategyInterface;

class TaskDeveloperAssignerStrategy implements TaskAssignerStrategyInterface
{
    private Developer $developer;

    public function __construct($developer)
    {
        $this->developer = $developer;
    }

    public function assignTask($task, $remainingHours): ?array
    {
        if ($task->getDifficulty() <= $this->developer->getDifficulty() && $task->getDuration() <= $remainingHours) {
            return [
                "developerId" => $this->developer->getId(),
                "developerName" => $this->developer->getDeveloperName(),
                "taskName" => $task->getTaskName(),
                "duration" => $task->getDuration()
            ];
        } else {
            return null;
        }
    }
}
