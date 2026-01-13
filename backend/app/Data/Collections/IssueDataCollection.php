<?php

namespace App\Data\Collections;

use App\Data\IssueData;
use Spatie\LaravelData\DataCollection;

class IssueDataCollection extends DataCollection
{
    public function __construct(array $items)
    {
        parent::__construct(IssueData::class, $items);
    }
}
