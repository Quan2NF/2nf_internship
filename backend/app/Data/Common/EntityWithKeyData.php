<?php

namespace App\Data\Common;

use Spatie\LaravelData\Data;

class EntityWithKeyData extends Data
{
    /**
     * @param KeyOnlyData $key
     * @param EntityData  $entity
     */
    public function __construct(
        public KeyOnlyData $key,
        public EntityData $entity
    ) {}

    public function getId(): int
    {
        return $this->key->id;
    }

    public function getEntity(): EntityData
    {
        return $this->entity;
    }
}
