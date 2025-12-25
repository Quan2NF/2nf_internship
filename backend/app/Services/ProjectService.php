<?php
namespace App\Services;

use App\Repositories\ProjectRepository;

class ProjectService
{
    public function __construct(
        protected ProjectRepository $projectRepository
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
        return $this->projectRepository->findById($id);
    }
}

