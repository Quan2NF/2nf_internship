<?php

namespace App\Contracts\Service;

use App\Http\Responses\ApiResponse;

use App\Data\Issue\IssueData;
use App\Data\Issue\IssueListFilterData;
use App\Data\Issue\PostCommentToIssueData;

/**
 * @extends BaseServiceInterface<IssueData>
 */
interface IssueServiceInterface extends BaseServiceInterface
{
    public function getList(IssueListFilterData $data): ApiResponse;
    
    public function postComment(PostCommentToIssueData $data): ApiResponse;
}