<?php
namespace App\Exceptions\Domain;

use Exception;

class BusinessException extends Exception
{
    protected int $status = 400;

    public function status(): int
    {
        return $this->status;
    }
}
