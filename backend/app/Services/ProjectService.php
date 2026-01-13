<?php
namespace App\Services;

use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    public function create(array $data, int $userId)
    {
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;
        $data['is_active'] = true;

        return $this->projectRepository->create($data);
    }

    public function list()
    {
        return $this->projectRepository->paginate();
    }

    public function detail(int $id)
    {
        return $this->projectRepository->find($id);
    }

    public function update(int $id, array $data, int $userId)
    {
        $data['updated_by'] = $userId;

        return $this->projectRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->projectRepository->delete($id);
    }

    public function restore(int $id)
    {
        $project = $this->projectRepository->find($id);
        if (!$project) {
            throw new \Exception('Project not found');
        }
        return $this->projectRepository->restore($id);
    }

    public function trashed()
    {
        return $this->projectRepository->getTrashed();
    }

    public function getByCreator(int $userId)
    {
        return $this->projectRepository->getByCreator($userId);
    }

    public function search(string $query)
    {
        return $this->projectRepository->search($query);
    }
}


