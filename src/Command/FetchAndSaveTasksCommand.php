<?php

namespace App\Command;

use App\Provider\TaskMapping\TaskMappingProvider;
use App\Repository\TaskRepository;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class FetchAndSaveTasksCommand extends Command
{
    protected static $defaultName = 'app:fetch-and-save-tasks';
    private $taskProvider;
    private $taskRepository;

    public function __construct(TaskMappingProvider $taskProvider, TaskRepository $taskRepository)
    {
        parent::__construct();

        $this->taskProvider = $taskProvider;
        $this->taskRepository = $taskRepository;
    }
    protected function configure(): void
    {
        $this
            ->setDescription('Fetch and save tasks from the given API')
            ->addOption('api-url', null, InputOption::VALUE_REQUIRED, 'The URL of the API to fetch tasks from');
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $apiUrl = $input->getOption('api-url');

        try {
            $tasks = $this->taskProvider->fetchAndProcessTasks($apiUrl);

            foreach ($tasks as $taskData) {
                $this->taskRepository->saveTask($taskData);
            }

            $output->writeln('Success');
            $exitCode = 0;
        } catch (Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            $exitCode = 1;
        }

        return $exitCode;
    }
}
