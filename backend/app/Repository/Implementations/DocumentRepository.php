<?php

namespace App\Repository\Implementations;

use App\Models\Document;
use App\Repository\Interfaces\DocumentRepositoryInterface;

class DocumentRepository extends BaseRepository implements DocumentRepositoryInterface
{
    public function __construct(Document $document)
    {
        parent::__construct($document);
    }
}
