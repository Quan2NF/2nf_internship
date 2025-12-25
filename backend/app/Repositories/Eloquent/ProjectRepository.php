<?php
namespace App\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function paginate(int $perPage = 15)
    {
        return Project::where('is_active', 1)
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): Project
    {
        return Project::findOrFail($id);
    }
}

