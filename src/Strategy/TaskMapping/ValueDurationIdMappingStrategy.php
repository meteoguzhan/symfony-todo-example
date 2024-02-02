<?php
namespace App\Strategy\TaskMapping;
use App\Interfaces\TaskMapping\TaskMappingStrategyInterface;

class ValueDurationIdMappingStrategy implements TaskMappingStrategyInterface
{
    public function map(array $task): array
    {
        return [
            'difficulty' => $task['value'],
            'duration' => $task['estimated_duration'],
            'taskName' => $task['id'],
        ];
    }
}
