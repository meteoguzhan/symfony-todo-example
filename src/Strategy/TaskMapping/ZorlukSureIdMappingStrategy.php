<?php
namespace App\Strategy\TaskMapping;
use App\Interfaces\TaskMapping\TaskMappingStrategyInterface;

class ZorlukSureIdMappingStrategy implements TaskMappingStrategyInterface
{
    public function map(array $task): array
    {
        return [
            'difficulty' => $task['zorluk'],
            'duration' => $task['sure'],
            'taskName' => $task['id'],
        ];
    }
}
