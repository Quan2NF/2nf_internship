<?php
namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Http\Request;

class ListTasksData extends Data
{
    public ?string $search = null;
    public ?int $status_id = null;
    public ?int $project_id = null;
    public ?int $per_page = 20;
    

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('search'),
            $request->input('status_id'),
            $request->input('project_id'),
            $request->input('per_page', 20)
        );
    }
}
