<?php
namespace App\Repositories;

use App\Data\ListTasksData;
use App\Data\CreateTaskData;
use App\Data\UpdateTaskData;
use App\Data\CreateTaskCommentData;

interface TaskRepositoryInterface
{
    public function paginateTasks(ListTasksData $filter);
    public function createTask(CreateTaskData $data);
    public function updateTask(int $id, UpdateTaskData $data);
    public function deleteTask(int $id);
    public function addComment(CreateTaskCommentData $data);
    public function getLogs(int $taskId);
}
