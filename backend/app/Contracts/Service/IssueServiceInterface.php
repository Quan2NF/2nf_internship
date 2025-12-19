<?php

namespace App\Contracts\Service;

use App\Data\Issue\IssueData;
use App\Data\Issue\IssueListFilterData;
use App\Data\Issue\PostCommentToIssueData;
use App\Data\Response\ApiResponseData;

/**
 * @extends BaseServiceInterface<IssueData>
 */
interface IssueServiceInterface extends BaseServiceInterface
{
    public function getList(IssueListFilterData $data): ApiResponseData;
    
    public function postComment(PostCommentToIssueData $data): ApiResponseData;
}