<?php

namespace App\Service;

use App\Contracts\Service\TaskServiceInterface;

use App\Data\Task\TaskListFilterData;
use App\Data\Task\PostCommentToTaskData;
use App\Data\Response\ApiResponseData;

class TaskService extends BaseService implements TaskServiceInterface
{
    public function getList(TaskListFilterData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
    
    public function postComment(PostCommentToTaskData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}