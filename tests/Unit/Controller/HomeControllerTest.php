<?php
namespace App\Tests\Unit\Controller;
use App\Controller\HomeController;
use App\Entity\Developer;
use App\Entity\Task;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;
use ReflectionProperty;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends KernelTestCase
{
    private TaskRepository $taskRepository;
    private DeveloperRepository $developerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->developerRepository = $this->createMock(DeveloperRepository::class);
    }

    public function testExecuteWithSuccess(): void
    {
        $controller = self::$container->get(HomeController::class);

        $developer1 = new Developer();
        $developer1->setDeveloperName('Developer 1');
        $developer1->setDifficulty(1);
        $developer1->setDuration(1);

        $developer2 = new Developer();
        $developer2->setDeveloperName('Developer 2');
        $developer2->setDifficulty(2);
        $developer2->setDuration(1);

        $task1 = new Task();
        $task1->setTaskName('Task 1');
        $task1->setDifficulty(1);
        $task1->setDuration(1);

        $task2 = new Task();
        $task2->setTaskName('Task 2');
        $task2->setDifficulty(2);
        $task2->setDuration(2);

        $this->developerRepository->expects($this->once())
            ->method('getAllDevelopers')
            ->willReturn([$developer1, $developer2]);

        $this->taskRepository->expects($this->once())
            ->method('getAllTasks')
            ->willReturn([$task1, $task2]);

        $reflectionDeveloperRepository = new ReflectionProperty(HomeController::class, 'developerRepository');
        $reflectionDeveloperRepository->setValue($controller, $this->developerRepository);

        $reflectionTaskRepository = new ReflectionProperty(HomeController::class, 'taskRepository');
        $reflectionTaskRepository->setValue($controller, $this->taskRepository);

        $response = $controller->index();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('items', $response->getContent());
    }

    public function testExecuteWithError(): void
    {
        $controller = self::$container->get(HomeController::class);

        $task1 = new Task();
        $task1->setTaskName('Task 1');
        $task1->setDifficulty(1);
        $task1->setDuration(1);

        $task2 = new Task();
        $task2->setTaskName('Task 2');
        $task2->setDifficulty(2);
        $task1->setDuration(2);

        $this->taskRepository->expects($this->once())
            ->method('getAllTasks')
            ->willReturn([$task1, $task2]);

        $this->developerRepository->expects($this->once())
            ->method('getAllDevelopers')
            ->willReturn([]);

        $reflectionTaskRepository = new ReflectionProperty(HomeController::class, 'taskRepository');
        $reflectionTaskRepository->setValue($controller, $this->taskRepository);

        $reflectionDeveloperRepository = new ReflectionProperty(HomeController::class, 'developerRepository');
        $reflectionDeveloperRepository->setValue($controller, $this->developerRepository);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Task developers could not be assigned.');
        $controller->index();
    }
}



