<?php

namespace App\Controller;

use App\Provider\TaskAssigner\TaskAssignerProvider;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private TaskRepository $taskRepository;
    private DeveloperRepository $developerRepository;

    public function __construct(TaskRepository $taskRepository, DeveloperRepository $developerRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->developerRepository = $developerRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $developers = $this->developerRepository->getAllDevelopers();
        $tasks = $this->taskRepository->getAllTasks();

        $taskAssigner = new TaskAssignerProvider($developers);
        $assignments = $taskAssigner->assignTasks($tasks, 45);

        return $this->render('home/index.html.twig', [
            'items' => $assignments,
        ]);
    }
}
