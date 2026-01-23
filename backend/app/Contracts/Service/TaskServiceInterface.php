<?php

namespace App\Contracts\Service;

use App\Models\Task;
use App\Models\Project;
use App\Data\Task\TaskData;
use App\Data\Task\TaskListFilterData;
use App\Data\Response\ApiResponseData;
use App\Data\Task\CommentData;

interface TaskServiceInterface
{
    public function getFilteredList(Project $project, TaskListFilterData $data): ApiResponseData;

    public function create(Project $project, TaskData $data): ApiResponseData;

    public function view(Task $task): ApiResponseData;

    public function update(Task $task, TaskData $data): ApiResponseData;

    public function delete(Task $task): ApiResponseData;
    
    public function postComment(Task $task, CommentData $data): ApiResponseData;

    public function getComments(Task $task): ApiResponseData;
}