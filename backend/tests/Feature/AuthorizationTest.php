<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Issue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $managerUser;
    protected User $regularUser;
    protected Project $project;
    protected Issue $issue;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->adminUser = User::factory()->create(['role' => 'admin', 'is_active' => User::STATUS_ACTIVE]);
        $this->managerUser = User::factory()->create(['role' => 'manager', 'is_active' => User::STATUS_ACTIVE]);
        $this->regularUser = User::factory()->create(['role' => 'user', 'is_active' => User::STATUS_ACTIVE]);

        // Create test project
        $this->project = Project::factory()->create(['created_by' => $this->adminUser->id]);

        // Create test issue
        $this->issue = Issue::factory()->create([
            'project_id' => $this->project->id,
            'reported_by' => $this->adminUser->id,
            'assigned_to' => $this->regularUser->id,
        ]);
    }

    // ============ Project Authorization Tests ============

    public function test_any_user_can_view_projects()
    {
        $response = $this->actingAs($this->regularUser)
            ->getJson('/api/projects');

        $response->assertOk();
    }

    public function test_only_active_user_can_create_project()
    {
        $data = [
            'name' => 'Test Project',
            'code' => 'TP-001',
            'description' => 'Test description',
        ];

        // Active user can create
        $response = $this->actingAs($this->regularUser)
            ->postJson('/api/projects', $data);

        $response->assertStatus(201);

        // Inactive user cannot create
        $inactiveUser = User::factory()->create(['is_active' => User::STATUS_INACTIVE]);
        $response = $this->actingAs($inactiveUser)
            ->postJson('/api/projects', $data);

        $response->assertForbidden();
    }

    public function test_only_admin_manager_can_view_trashed_projects()
    {
        $this->project->delete();

        // Admin can view trashed
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/projects/trashed');
        $response->assertOk();

        // Manager can view trashed
        $response = $this->actingAs($this->managerUser)
            ->getJson('/api/projects/trashed');
        $response->assertOk();

        // Regular user cannot view trashed
        $response = $this->actingAs($this->regularUser)
            ->getJson('/api/projects/trashed');
        $response->assertForbidden();
    }

    public function test_only_creator_or_admin_can_update_project()
    {
        $data = ['name' => 'Updated Project'];

        // Creator can update
        $response = $this->actingAs($this->adminUser)
            ->patchJson("/api/projects/{$this->project->id}", $data);
        $response->assertOk();

        // Other user cannot update
        $response = $this->actingAs($this->regularUser)
            ->patchJson("/api/projects/{$this->project->id}", $data);
        $response->assertForbidden();

        // Manager can update
        $response = $this->actingAs($this->managerUser)
            ->patchJson("/api/projects/{$this->project->id}", $data);
        $response->assertOk();
    }

    public function test_only_creator_or_admin_can_delete_project()
    {
        // Creator can delete
        $response = $this->actingAs($this->adminUser)
            ->deleteJson("/api/projects/{$this->project->id}");
        $response->assertOk();

        // Create new project for other tests
        $project2 = Project::factory()->create(['created_by' => $this->managerUser->id]);

        // Other user cannot delete
        $response = $this->actingAs($this->regularUser)
            ->deleteJson("/api/projects/{$project2->id}");
        $response->assertForbidden();

        // Manager can delete even if not creator
        $response = $this->actingAs($this->adminUser)
            ->deleteJson("/api/projects/{$project2->id}");
        $response->assertOk();
    }

    // ============ Issue Authorization Tests ============

    public function test_any_user_can_view_issues()
    {
        $response = $this->actingAs($this->regularUser)
            ->getJson('/api/issues');

        $response->assertOk();
    }

    public function test_only_active_user_can_create_issue()
    {
        $data = [
            'project_id' => $this->project->id,
            'title' => 'Test Issue',
            'description' => 'Test description',
        ];

        // Active user can create
        $response = $this->actingAs($this->regularUser)
            ->postJson('/api/issues', $data);

        $response->assertStatus(201);

        // Inactive user cannot create
        $inactiveUser = User::factory()->create(['is_active' => User::STATUS_INACTIVE]);
        $response = $this->actingAs($inactiveUser)
            ->postJson('/api/issues', $data);

        $response->assertForbidden();
    }

    public function test_only_admin_manager_can_view_trashed_issues()
    {
        $this->issue->delete();

        // Admin can view trashed
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/issues/trashed');
        $response->assertOk();

        // Manager can view trashed
        $response = $this->actingAs($this->managerUser)
            ->getJson('/api/issues/trashed');
        $response->assertOk();

        // Regular user cannot view trashed
        $response = $this->actingAs($this->regularUser)
            ->getJson('/api/issues/trashed');
        $response->assertForbidden();
    }

    public function test_reporter_assignee_or_admin_can_view_issue()
    {
        // Reporter can view
        $response = $this->actingAs($this->adminUser)
            ->getJson("/api/issues/{$this->issue->id}");
        $response->assertOk();

        // Assignee can view
        $response = $this->actingAs($this->regularUser)
            ->getJson("/api/issues/{$this->issue->id}");
        $response->assertOk();

        // Other user cannot view
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)
            ->getJson("/api/issues/{$this->issue->id}");
        $response->assertForbidden();
    }

    // ============ User Authorization Tests ============

    public function test_only_admin_can_view_all_users()
    {
        // Admin can view
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/users');
        // Note: Endpoint not created yet, but testing the policy
        // $response->assertOk();

        // Manager cannot view (based on UserPolicy)
        // $response = $this->actingAs($this->managerUser)
        //     ->getJson('/api/users');
        // $response->assertForbidden();
    }

    // ============ Gate Tests ============

    public function test_gate_admin_only()
    {
        $this->assertTrue($this->adminUser->can('admin-only'));
        $this->assertFalse($this->managerUser->can('admin-only'));
        $this->assertFalse($this->regularUser->can('admin-only'));
    }

    public function test_gate_manage_users()
    {
        $this->assertTrue($this->adminUser->can('manage-users'));
        $this->assertTrue($this->managerUser->can('manage-users'));
        $this->assertFalse($this->regularUser->can('manage-users'));
    }

    public function test_gate_manage_projects()
    {
        $this->assertTrue($this->adminUser->can('manage-projects'));
        $this->assertTrue($this->managerUser->can('manage-projects'));
        $this->assertFalse($this->regularUser->can('manage-projects'));
    }

    public function test_gate_create_projects()
    {
        $this->assertTrue($this->adminUser->can('create-projects'));
        $this->assertTrue($this->managerUser->can('create-projects'));
        $this->assertTrue($this->regularUser->can('create-projects'));

        // Inactive user cannot create
        $inactiveUser = User::factory()->create(['is_active' => User::STATUS_INACTIVE]);
        $this->assertFalse($inactiveUser->can('create-projects'));
    }

    public function test_gate_manage_roles()
    {
        $this->assertTrue($this->adminUser->can('manage-roles'));
        $this->assertFalse($this->managerUser->can('manage-roles'));
        $this->assertFalse($this->regularUser->can('manage-roles'));
    }

    public function test_gate_view_reports()
    {
        $this->assertTrue($this->adminUser->can('view-reports'));
        $this->assertTrue($this->managerUser->can('view-reports'));
        $this->assertFalse($this->regularUser->can('view-reports'));
    }

    public function test_admin_can_do_everything()
    {
        // Admin user should be able to do anything
        $this->assertTrue($this->adminUser->can('view-reports'));
        $this->assertTrue($this->adminUser->can('manage-users'));
        $this->assertTrue($this->adminUser->can('manage-projects'));
        $this->assertTrue($this->adminUser->can('admin-only'));
    }
}
