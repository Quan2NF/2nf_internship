<?php
namespace App\Exceptions\Domain;

class NotFoundException extends BusinessException
{
    protected int $status = 404;
}
