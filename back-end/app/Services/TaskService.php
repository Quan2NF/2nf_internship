<?php
namespace App\Services;

use App\Repositories\TaskRepositoryInterface;
use App\Data\ListTasksData;
use App\Data\CreateTaskData;
use App\Data\UpdateTaskData;
use App\Data\CreateTaskCommentData;

class TaskService implements TaskServiceInterface
{
    public function __construct(private readonly TaskRepositoryInterface $repo) {}
    public function listTasks(ListTasksData $filter)
    {
        return $this->repo->paginateTasks($filter);
    }
    public function createTask(CreateTaskData $data)
    {
        return $this->repo->createTask($data);
    }
    public function updateTask(int $id, UpdateTaskData $data)
    {
        return $this->repo->updateTask($id, $data);
    }
    public function deleteTask(int $id)
    {
        return $this->repo->deleteTask($id);
    }
    public function addComment(CreateTaskCommentData $data)
    {
        return $this->repo->addComment($data);
    }
    public function getLogs(int $taskId)
    {
        return $this->repo->getLogs($taskId);
    }
}
