<?php

namespace App\Contracts\Service;

use App\Data\Task\TaskData;
use App\Data\Task\TaskListFilterData;
use App\Data\Task\PostCommentToTaskData;
use App\Data\Response\ApiResponseData;

interface TaskServiceInterface extends BaseServiceInterface
{
    public function getList(TaskListFilterData $data): ApiResponseData;
    
    public function postComment(PostCommentToTaskData $data): ApiResponseData;
}