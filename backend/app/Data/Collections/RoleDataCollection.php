<?php

namespace App\Data\Collections;

use App\Data\RoleData;
use Spatie\LaravelData\DataCollection;

class RoleDataCollection extends DataCollection
{
    public function __construct(array $items)
    {
        parent::__construct(RoleData::class, $items);
    }
}

