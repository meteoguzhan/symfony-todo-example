<?php
namespace App\Tests\Unit\Command;

use App\Command\FetchAndSaveTasksCommand;
use App\Provider\TaskMapping\TaskMappingProvider;
use App\Repository\TaskRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class FetchAndSaveTasksCommandTest extends KernelTestCase
{
    private $taskProvider;
    private $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->taskProvider = $this->createMock(TaskMappingProvider::class);
        $this->taskRepository = $this->createMock(TaskRepository::class);
    }
    public function testExecuteWithSuccess(): void
    {
        $tasks = [
            ['id' => 'Task 1', 'estimated_duration' => 1, 'value' => 1],
            ['id' => 'Task 2', 'estimated_duration' => 2, 'value' => 2],
            ['id' => 'Task 3', 'sure' => 1, 'zorluk' => 1],
            ['id' => 'Task 4', 'sure' => 2, 'zorluk' => 2],
        ];

        $this->taskProvider->expects($this->once())
            ->method('fetchAndProcessTasks')
            ->willReturn($tasks);

        $this->taskRepository->expects($this->exactly(count($tasks)))
            ->method('saveTask');

        $application = new Application();
        $command = new FetchAndSaveTasksCommand($this->taskProvider, $this->taskRepository);
        $application->add($command);

        $command = $application->find('app:fetch-and-save-tasks');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--api-url' => 'https://example.com/api/tasks',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Success', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }

    public function testExecuteWithAPIError(): void
    {
        $this->taskProvider->expects($this->once())
            ->method('fetchAndProcessTasks')
            ->willThrowException(new Exception('API error'));

        $application = new Application();
        $command = new FetchAndSaveTasksCommand($this->taskProvider, $this->taskRepository);
        $application->add($command);

        $command = $application->find('app:fetch-and-save-tasks');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            '--api-url' => 'https://example.com/api/tasks',
        ]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Error: API error', $output);
        $this->assertEquals(1, $commandTester->getStatusCode());
    }
}
