<?php
namespace App\Interfaces\TaskAssigner;

interface TaskAssignerStrategyInterface
{
    public function assignTask($task, $remainingHours);
}
