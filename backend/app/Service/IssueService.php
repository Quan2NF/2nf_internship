<?php

namespace App\Service;

use App\Contracts\Service\IssueServiceInterface;

use App\Data\Issue\IssueListFilterData;
use App\Data\Issue\PostCommentToIssueData;
use App\Data\Response\ApiResponseData;

class IssueService extends BaseService implements IssueServiceInterface
{
    public function getList(IssueListFilterData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
    
    public function postComment(PostCommentToIssueData $data): ApiResponseData
    {
        throw new \Exception('Not implemented');
    }
}