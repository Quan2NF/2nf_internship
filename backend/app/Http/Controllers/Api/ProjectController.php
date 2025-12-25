<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->projectService->list()
        ]);
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->projectService->create(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $project
        ], 201);
    }

    public function show(int $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->projectService->detail($id)
        ]);
    }
}

