<?php
namespace App\Exceptions\Domain;

class ForbiddenException extends BusinessException
{
    protected int $status = 403;
}
