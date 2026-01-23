<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Project;
use App\Data\Task\TaskData;
use Illuminate\Http\Request;
use App\Data\Task\CommentData;
use App\Http\Controllers\Controller;
use App\Data\Task\TaskListFilterData;
use App\Data\Response\ApiResponseData;
use App\Http\Requests\Task\ViewTaskRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\DeleteTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\GetCommentsRequest;
use App\Http\Requests\Task\PostCommentRequest;
use App\Contracts\Service\TaskServiceInterface;
use App\Http\Requests\Task\GetFilteredTaskListRequest;

class TaskController extends Controller
{
    public function __construct(
        protected TaskServiceInterface $taskService
    ){}

    public function getFilteredList(Project $project, GetFilteredTaskListRequest $request): ApiResponseData
    {
        return $this->taskService->getFilteredList($project, TaskListFilterData::from($request->validated()));
    }

    public function create(Project $project, CreateTaskRequest $request): ApiResponseData
    {
        return $this->taskService->create($project, TaskData::from($request->validated()));
    }

    public function view(Project $project, Task $task, ViewTaskRequest $request): ApiResponseData
    {
        return $this->taskService->view($task);
    }

    public function update(Project $project, Task $task, UpdateTaskRequest $request): ApiResponseData
    {
        return $this->taskService->update($task, TaskData::from($request->validated()));
    }

    public function delete(Project $project, Task $task, DeleteTaskRequest $request): ApiResponseData
    {
        return $this->taskService->delete($task);
    }

    public function getComments(Project $project, Task $task, GetCommentsRequest $request): ApiResponseData
    {
        return $this->taskService->getComments($task);
    }

    public function postComment(Project $project, Task $task, PostCommentRequest $request): ApiResponseData
    {
        return $this->taskService->postComment($task, CommentData::from($request->validated()));
    }
}
