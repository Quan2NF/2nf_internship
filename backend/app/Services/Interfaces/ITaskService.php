<?php

namespace App\Services\Interfaces;

interface ITaskService
{
    /**
     * @return array{items: array, total: int, page: int, total_pages: int}
     */
    public function list(array $filter, int $perPage, int $userId): array;

    /**
     * @return array TaskData payload
     */
    public function create(array $data, int $userId): array;

    /**
     * @return array TaskData payload
     */
    public function update(int $id, array $data, int $userId): array;

    public function delete(int $id, int $userId): void;

    public function comment(int $taskId, string $content, int $userId): void;

    public function logs(int $taskId, int $userId): array;

}
