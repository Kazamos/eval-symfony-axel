<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    public function __construct(
        private TaskService $taskService
    ) {}

    #[Route('/task/add', name: 'app_task_add', methods: ['GET', 'POST'])]
    public function addTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $response = $this->taskService->saveTask($task);
                $this->addFlash($response['type'], $response['message']);
                return $this->redirectToRoute('app_task_add');
            } catch (\Exception $e) {
                $this->addFlash('danger', "Erreur système : " . $e->getMessage());
            }
        }

        return $this->render('task/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/tasks', name: 'app_tasks', methods: ['GET'])]
    public function list(): Response
    {
        try {
            $tasks = $this->taskService->getAllTasks();
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
            $tasks = [];
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }
}
