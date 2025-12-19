<?php

namespace App\Service;

use App\Service\Interfaces\IssueServiceInterface;
use App\Http\Responses\ApiResponse;

use App\Data\Issue\IssueData;
use App\Data\Issue\IssueListFilterData;
use App\Data\Issue\PostCommentToIssueData;

class IssueService extends BaseService implements IssueServiceInterface
{
    public function getList(IssueListFilterData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
    
    public function postComment(PostCommentToIssueData $data): ApiResponse
    {
        throw new \Exception('Not implemented');
    }
}