<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
    ) {}

    public function saveTask(Task $task): array
    {
        try {
            if ($this->taskRepository->findOneBy(["title" => $task->getTitle()])) {
                return ["type"=>"danger", "message"=> "la tâche existe déjà"];
            }
            
            $this->taskRepository->save($task);

            return ["type"=>"success", "message"=> "la tâche a été ajouté"];
        } catch (\Exception $e) {
            throw new \Exception("Désolé, impossible de créer la tâche pour le moment. Détails : " . $e->getMessage());
        }
    }

    public function getAllTasks(): array
    {
        try {
            $tasks = $this->taskRepository->findAllTasks();
            return $tasks;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la récupération de la liste des tâches.");
        }
    }
}