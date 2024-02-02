<?php
namespace App\Strategy\TaskAssigner;

use App\Interfaces\TaskAssigner\TaskAssignerStrategyInterface;

class TaskDeveloperAssignerStrategy implements TaskAssignerStrategyInterface
{
    private $developerId;
    private $developerName;
    private $difficulty;

    public function __construct($developerId, $developerName, $difficulty)
    {
        $this->developerId = $developerId;
        $this->developerName = $developerName;
        $this->difficulty = $difficulty;
    }

    public function assignTask($task, $remainingHours): ?array
    {
        if ($task["difficulty"] <= $this->difficulty && $task["duration"] <= $remainingHours) {
            return [
                "developerId" => $this->developerId,
                "developerName" => $this->developerName,
                "taskName" => $task["taskName"],
                "duration" => $task["duration"]
            ];
        } else {
            return null;
        }
    }
}
