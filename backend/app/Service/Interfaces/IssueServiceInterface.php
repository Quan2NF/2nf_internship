<?php

namespace App\Service\Interfaces;

use App\Http\Responses\ApiResponse;

use App\Data\Issue\GetListIssueRequestData;
use App\Data\Issue\CreateIssueRequestData;
use App\Data\Issue\EditIssueRequestData;
use App\Data\Issue\DeleteIssueRequestData;
use App\Data\Issue\PostCommentIssueRequestData;

interface IssueServiceInterface
{
    public function GetList(GetListIssueRequestData $data): ApiResponse;

    public function Create(CreateIssueRequestData $data): ApiResponse;

    public function Edit(EditIssueRequestData $data): ApiResponse;

    public function Delete(DeleteIssueRequestData $data): ApiResponse;

    public function PostComment(PostCommentIssueRequestData $data): ApiResponse;
}