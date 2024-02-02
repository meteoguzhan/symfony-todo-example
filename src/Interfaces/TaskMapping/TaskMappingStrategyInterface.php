<?php
namespace App\Interfaces\TaskMapping;

interface TaskMappingStrategyInterface
{
    public function map(array $task): array;
}
