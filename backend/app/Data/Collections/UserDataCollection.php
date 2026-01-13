<?php

namespace App\Data\Collections;

use App\Data\Auth\UserData;
use Spatie\LaravelData\DataCollection;

class UserDataCollection extends DataCollection
{
    public function __construct(array $items)
    {
        parent::__construct(UserData::class, $items);
    }
}
