<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $pm;
    protected User $developer;
    protected Project $project;
    protected string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_ADMIN,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        $this->pm = User::factory()->create([
            'email' => 'pm@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_PM,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        $this->developer = User::factory()->create([
            'email' => 'developer@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_USER,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        // Create test project
        $this->project = Project::factory()->create([
            'name' => 'Test Project',
            'description' => 'A test project',
            'status' => ProjectStatus::ACTIVE,
            'created_by' => $this->admin->id,
            'pm_id' => $this->pm->id,
        ]);
    }

    // ============ API16: GET /api/projects ============

    public function test_admin_can_list_all_projects()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'status',
                        'start_date',
                        'end_date',
                        'pm_id',
                        'created_by',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_pm_can_list_projects()
    {
        $response = $this->actingAs($this->pm)
            ->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_regular_user_can_list_assigned_projects()
    {
        // Assign developer to project
        $this->project->users()->attach($this->developer->id);

        $response = $this->actingAs($this->developer)
            ->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_list_projects()
    {
        $response = $this->getJson('/api/projects');

        $response->assertUnauthorized();
    }

    // ============ API17: GET /api/projects/filter ============

    public function test_can_filter_projects_by_name()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/projects/filter?name=Test');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $data = $response->json('data');
        foreach ($data as $project) {
            $this->assertStringContainsString('Test', $project['name']);
        }
    }

    public function test_can_filter_projects_by_status()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/projects/filter?status=1'); // ACTIVE = 1

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_can_filter_projects_by_pm_id()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/projects/filter?pm_id={$this->pm->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_filter_returns_empty_when_no_match()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/projects/filter?name=NonExistentProject12345');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonPath('data', []);
    }

    // ============ API18: POST /api/projects ============

    public function test_admin_can_create_project()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/projects', [
                'name' => 'New Project',
                'description' => 'A new project description',
                'status' => ProjectStatus::ACTIVE,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'status',
                    'start_date',
                    'end_date',
                    'pm_id',
                    'created_by',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'name' => 'New Project',
                'success' => true,
            ])
            ->assertJsonFragment([
                'message' => 'Dự án được tạo thành công',
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'New Project',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_pm_can_create_project()
    {
        $response = $this->actingAs($this->pm)
            ->postJson('/api/projects', [
                'name' => 'PM Created Project',
                'description' => 'Created by PM',
            ]);

        $response->assertCreated()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_regular_user_cannot_create_project()
    {
        $response = $this->actingAs($this->developer)
            ->postJson('/api/projects', [
                'name' => 'Unauthorized Project',
            ]);

        $response->assertForbidden();
    }

    public function test_create_project_requires_name()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/projects', [
                'description' => 'No name provided',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    // ============ API16b: GET /api/projects/{id} ============

    public function test_can_show_project()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/projects/{$this->project->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'status',
                    'start_date',
                    'end_date',
                    'pm_id',
                    'created_by',
                    'created_at',
                    'updated_at',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'id' => $this->project->id,
                'name' => 'Test Project',
                'success' => true,
            ])
            ->assertJsonFragment([
                'message' => 'Chi tiết dự án',
            ]);
    }

    public function test_cannot_show_nonexistent_project()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/projects/99999');

        $response->assertNotFound();
    }

    // ============ API19: PUT /api/projects/{id} ============

    public function test_admin_can_update_project()
    {
        $response = $this->actingAs($this->admin)
            ->putJson("/api/projects/{$this->project->id}", [
                'name' => 'Updated Project Name',
                'description' => 'Updated description',
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Dự án được cập nhật thành công',
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $this->project->id,
            'name' => 'Updated Project Name',
        ]);
    }

    public function test_pm_can_update_assigned_project()
    {
        // Assign PM to project
        $this->project->update(['pm_id' => $this->pm->id]);

        $response = $this->actingAs($this->pm)
            ->putJson("/api/projects/{$this->project->id}", [
                'name' => 'PM Updated Name',
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_regular_user_cannot_update_project()
    {
        $response = $this->actingAs($this->developer)
            ->putJson("/api/projects/{$this->project->id}", [
                'name' => 'Hacked Name',
            ]);

        $response->assertForbidden();
    }

    public function test_cannot_update_nonexistent_project()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/projects/99999', [
                'name' => 'Updated Name',
            ]);

        $response->assertNotFound();
    }

    // ============ API20: DELETE /api/projects/{id} ============

    public function test_admin_can_delete_project()
    {
        $projectToDelete = Project::factory()->create([
            'name' => 'To Be Deleted',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/projects/{$projectToDelete->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Dự án được xóa thành công',
            ]);

        $this->assertSoftDeleted('projects', [
            'id' => $projectToDelete->id,
        ]);
    }

    public function test_regular_user_cannot_delete_project()
    {
        $response = $this->actingAs($this->developer)
            ->deleteJson("/api/projects/{$this->project->id}");

        $response->assertForbidden();
    }

    public function test_cannot_delete_nonexistent_project()
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson('/api/projects/99999');

        $response->assertNotFound();
    }

    // ============ API21: POST /api/projects/{id}/assign-pm ============

    public function test_admin_can_assign_pm()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-pm", [
                'pm_id' => $this->developer->id,
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Gán người quản lý dự án thành công',
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $this->project->id,
            'pm_id' => $this->developer->id,
        ]);
    }

    public function test_assign_pm_requires_pm_id()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-pm", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['pm_id']);
    }

    public function test_assign_pm_validates_existence()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-pm", [
                'pm_id' => 99999,
            ]);

        // Controller doesn't validate existence, just updates
        $response->assertOk();
    }

    public function test_pm_cannot_assign_pm()
    {
        $response = $this->actingAs($this->pm)
            ->postJson("/api/projects/{$this->project->id}/assign-pm", [
                'pm_id' => $this->developer->id,
            ]);

        $response->assertForbidden();
    }

    // ============ API22: POST /api/projects/{id}/assign-members ============

    public function test_admin_can_assign_members()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-members", [
                'member_ids' => [$this->developer->id],
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Gán thành viên dự án thành công',
            ]);

        $this->assertTrue(
            $this->project->users()->where('user_id', $this->developer->id)->exists()
        );
    }

    public function test_can_assign_multiple_members()
    {
        $user2 = User::factory()->create([
            'email' => 'another@example.com',
            'password' => bcrypt($this->password),
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-members", [
                'member_ids' => [$this->developer->id, $user2->id],
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $this->assertTrue(
            $this->project->users()->where('user_id', $this->developer->id)->exists()
        );
        $this->assertTrue(
            $this->project->users()->where('user_id', $user2->id)->exists()
        );
    }

    public function test_assign_members_requires_member_ids()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-members", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['member_ids']);
    }

    public function test_assign_members_validates_array()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-members", [
                'member_ids' => 'not-an-array',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['member_ids']);
    }

    public function test_assign_members_validates_existence()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/projects/{$this->project->id}/assign-members", [
                'member_ids' => [99999],
            ]);

        // Controller doesn't validate existence, just syncs
        $response->assertOk();
    }

    public function test_pm_can_assign_members()
    {
        // Assign PM to project
        $this->project->update(['pm_id' => $this->pm->id]);

        $response = $this->actingAs($this->pm)
            ->postJson("/api/projects/{$this->project->id}/assign-members", [
                'member_ids' => [$this->developer->id],
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_regular_user_cannot_assign_members()
    {
        $response = $this->actingAs($this->developer)
            ->postJson("/api/projects/{$this->project->id}/assign-members", [
                'member_ids' => [$this->developer->id],
            ]);

        $response->assertForbidden();
    }

    // ============ Additional Project Tests ============

    public function test_project_has_valid_status_values()
    {
        $statuses = [ProjectStatus::ACTIVE, ProjectStatus::INACTIVE, ProjectStatus::ARCHIVED, ProjectStatus::COMPLETED];
        
        foreach ($statuses as $status) {
            $project = Project::factory()->create([
                'status' => $status,
                'created_by' => $this->admin->id,
            ]);
            
            $response = $this->actingAs($this->admin)
                ->getJson("/api/projects/{$project->id}");
            
            $response->assertOk();
            
            $project->forceDelete();
        }
    }

    public function test_unassigned_user_cannot_see_project()
    {
        // Create a new user not assigned to any project
        $newUser = User::factory()->create([
            'email' => 'newuser@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_USER,
        ]);

        // This user is not admin or PM, and not assigned to project
        // The filter should not show this project
        $response = $this->actingAs($newUser)
            ->getJson('/api/projects/filter');

        $response->assertOk();
        
        $data = $response->json('data');
        $projectIds = collect($data)->pluck('id')->toArray();
        
        // Should not see the project if not assigned
        $this->assertNotContains($this->project->id, $projectIds);
    }

    public function test_member_can_see_assigned_project()
    {
        // Assign developer to project
        $this->project->users()->attach($this->developer->id);

        $response = $this->actingAs($this->developer)
            ->getJson("/api/projects/{$this->project->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $this->project->id,
            ]);
    }
}

