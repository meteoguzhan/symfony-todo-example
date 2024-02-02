<?php

namespace App\Provider\TaskMapping;

use App\Factory\TaskMapping\TaskMappingStrategyFactory;
use App\Interfaces\TaskMapping\TaskMappingStrategyInterface;
use App\Service\ApiService;
use Exception;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TaskMappingProvider
{
    private $mappingStrategy;
    private $apiService;

    public function __construct(TaskMappingStrategyInterface $mappingStrategy, ApiService $apiService)
    {
        $this->mappingStrategy = $mappingStrategy;
        $this->apiService = $apiService;
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function fetchAndProcessTasks(string $apiUrl): array
    {
        $responseData = $this->apiService->fetchDataFromApi($apiUrl);
        return $this->processTasks($responseData);
    }

    private function processTasks(array $tasks): array
    {
        $processedTasks = [];
        foreach ($tasks as $task) {
            $this->setMappingStrategy((new TaskMappingStrategyFactory)->create($task));
            $processedTask = $this->mappingStrategy->map($task);
            if ($this->isValidTask($processedTask)) {
                $processedTasks[] = $processedTask;
            } else {
                throw new RuntimeException('Task data does not conform to a valid schema.');
            }
        }

        return $processedTasks;
    }

    private function setMappingStrategy(TaskMappingStrategyInterface $mappingStrategy): void
    {
        $this->mappingStrategy = $mappingStrategy;
    }

    private function isValidTask(array $task): bool
    {
        return !empty($task['difficulty']) && !empty($task['duration']) && !empty($task['taskName']);
    }
}
