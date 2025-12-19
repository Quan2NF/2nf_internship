<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\Issue\IssueData;
use App\Data\Issue\IssueListFilterData;
use App\Data\Issue\PostCommentToIssueData;

interface IssueServiceInterface
{
    public function getList(IssueListFilterData $data): ApiResponse;

    public function create(IssueData $data): ApiResponse;

    public function edit(IssueData $data): ApiResponse;

    public function delete(IssueData $data): ApiResponse;

    public function postComment(PostCommentToIssueData $data): ApiResponse;
}