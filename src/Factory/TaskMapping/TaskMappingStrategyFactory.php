<?php
namespace App\Factory\TaskMapping;

use App\Interfaces\TaskMapping\TaskMappingStrategyInterface;
use App\Strategy\TaskMapping\ValueDurationIdMappingStrategy;
use App\Strategy\TaskMapping\ZorlukSureIdMappingStrategy;
use RuntimeException;

class TaskMappingStrategyFactory
{
    private $strategies;

    public function __construct()
    {
        $this->strategies = [
            'zorluk' => new ZorlukSureIdMappingStrategy(),
            'value' => new ValueDurationIdMappingStrategy(),
        ];
    }

    public function create(array $task): TaskMappingStrategyInterface
    {
        foreach ($this->strategies as $key => $strategy) {
            if (isset($task[$key])) {
                return $strategy;
            }
        }

        throw new RuntimeException('Could not determine the mapping strategy for the given task.');
    }
}
