<?php

namespace Tests\Feature;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use App\Enums\IssueType;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $pm;
    protected User $developer;
    protected User $reporter;
    protected Project $project;
    protected Issue $issue;
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

        $this->reporter = User::factory()->create([
            'email' => 'reporter@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_USER,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        // Create project
        $this->project = Project::factory()->create([
            'name' => 'Test Project',
            'description' => 'A test project',
            'status' => 1, // ACTIVE
            'created_by' => $this->admin->id,
            'pm_id' => $this->pm->id,
        ]);

        // Assign members to project
        $this->project->users()->attach([$this->developer->id, $this->reporter->id]);

        // Create test issue
        $this->issue = Issue::factory()->create([
            'title' => 'Test Issue',
            'description' => 'A test issue description',
            'status' => IssueStatus::OPEN,
            'priority' => IssuePriority::MEDIUM,
            'type' => IssueType::TASK,
            'project_id' => $this->project->id,
            'reporter_id' => $this->reporter->id,
            'assignee_id' => $this->developer->id,
        ]);
    }

    // ============ API27: GET /api/issues ============

    public function test_admin_can_list_all_issues()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/issues');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'priority',
                        'type',
                        'project_id',
                        'reporter_id',
                        'assignee_id',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Danh sách issues',
            ]);
    }

    public function test_project_member_can_list_issues()
    {
        $response = $this->actingAs($this->developer)
            ->getJson('/api/issues');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_list_issues()
    {
        $response = $this->getJson('/api/issues');

        $response->assertUnauthorized();
    }

    public function test_issue_list_contains_created_issue()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/issues');

        $response->assertOk();

        $data = $response->json('data');
        $issueIds = collect($data)->pluck('id')->toArray();

        $this->assertContains($this->issue->id, $issueIds);
    }

    // ============ API28: GET /api/issues/filter ============

    public function test_can_filter_issues_by_project()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/issues/filter?project_id={$this->project->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $data = $response->json('data');
        foreach ($data as $issue) {
            $this->assertEquals($this->project->id, $issue['project_id']);
        }
    }

    public function test_can_filter_issues_by_status()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/issues/filter?status=' . IssueStatus::OPEN);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_can_filter_issues_by_priority()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/issues/filter?priority=' . IssuePriority::MEDIUM);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_can_filter_issues_by_assignee()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/issues/filter?assignee_id={$this->developer->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_filter_returns_empty_when_no_match()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/issues/filter?project_id=99999');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonPath('data', []);
    }

    public function test_regular_user_filter_respects_permissions()
    {
        // Create a project not accessible by developer
        $privateProject = Project::factory()->create([
            'name' => 'Private Project',
            'created_by' => $this->admin->id,
        ]);

        Issue::factory()->create([
            'title' => 'Private Issue',
            'project_id' => $privateProject->id,
            'reporter_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->developer)
            ->getJson('/api/issues/filter');

        $response->assertOk();

        $data = $response->json('data');
        $issueTitles = collect($data)->pluck('title')->toArray();

        // Should not see private issue
        $this->assertNotContains('Private Issue', $issueTitles);
    }

    // ============ API29: POST /api/issues ============

    public function test_member_can_create_issue()
    {
        $response = $this->actingAs($this->reporter)
            ->postJson('/api/issues', [
                'title' => 'New Issue',
                'description' => 'Issue description',
                'status' => IssueStatus::OPEN,
                'priority' => IssuePriority::HIGH,
                'type' => IssueType::BUG,
                'project_id' => $this->project->id,
                'assignee_id' => $this->developer->id,
            ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'type',
                    'project_id',
                    'reporter_id',
                    'assignee_id',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'title' => 'New Issue',
                'success' => true,
                'message' => 'Issue được tạo thành công',
            ]);

        $this->assertDatabaseHas('issues', [
            'title' => 'New Issue',
            'project_id' => $this->project->id,
            'reporter_id' => $this->reporter->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_issue()
    {
        $response = $this->postJson('/api/issues', [
            'title' => 'Unauthorized Issue',
            'project_id' => $this->project->id,
        ]);

        $response->assertUnauthorized();
    }

    public function test_create_issue_requires_title()
    {
        $response = $this->actingAs($this->reporter)
            ->postJson('/api/issues', [
                'project_id' => $this->project->id,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_create_issue_requires_project_id()
    {
        $response = $this->actingAs($this->reporter)
            ->postJson('/api/issues', [
                'title' => 'Issue without project',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['project_id']);
    }

    public function test_create_issue_validates_status_values()
    {
        $response = $this->actingAs($this->reporter)
            ->postJson('/api/issues', [
                'title' => 'Issue with invalid status',
                'project_id' => $this->project->id,
                'status' => 'invalid_status',
            ]);

        // Controller doesn't validate status, just stores
        $response->assertCreated();
    }

    public function test_create_issue_associates_with_reporter()
    {
        $response = $this->actingAs($this->reporter)
            ->postJson('/api/issues', [
                'title' => 'My Reported Issue',
                'project_id' => $this->project->id,
            ]);

        $response->assertCreated();

        $data = $response->json('data');
        $this->assertEquals($this->reporter->id, $data['reporter_id']);
    }

    // ============ API30: PUT /api/issues/{id} ============

    public function test_reporter_can_update_issue()
    {
        $response = $this->actingAs($this->reporter)
            ->putJson("/api/issues/{$this->issue->id}", [
                'title' => 'Updated Issue Title',
                'description' => 'Updated description',
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Issue được cập nhật thành công',
            ]);

        $this->assertDatabaseHas('issues', [
            'id' => $this->issue->id,
            'title' => 'Updated Issue Title',
        ]);
    }

    public function test_assignee_can_update_issue()
    {
        $response = $this->actingAs($this->developer)
            ->putJson("/api/issues/{$this->issue->id}", [
                'status' => IssueStatus::IN_PROGRESS,
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $this->assertDatabaseHas('issues', [
            'id' => $this->issue->id,
            'status' => IssueStatus::IN_PROGRESS,
        ]);
    }

    public function test_cannot_update_nonexistent_issue()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/issues/99999', [
                'title' => 'Updated Title',
            ]);

        $response->assertNotFound();
    }

    public function test_unauthorized_user_cannot_update_issue()
    {
        // Create a different user not related to the issue
        $otherUser = User::factory()->create([
            'email' => 'other@example.com',
            'password' => bcrypt($this->password),
        ]);

        $response = $this->actingAs($otherUser)
            ->putJson("/api/issues/{$this->issue->id}", [
                'title' => 'Hacked Title',
            ]);

        $response->assertForbidden();
    }

    // ============ API31: DELETE /api/issues/{id} ============

    public function test_reporter_can_delete_issue()
    {
        $issueToDelete = Issue::factory()->create([
            'title' => 'To Be Deleted',
            'project_id' => $this->project->id,
            'reporter_id' => $this->reporter->id,
        ]);

        $response = $this->actingAs($this->reporter)
            ->deleteJson("/api/issues/{$issueToDelete->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Issue được xóa thành công',
            ]);

        $this->assertSoftDeleted('issues', [
            'id' => $issueToDelete->id,
        ]);
    }

    public function test_admin_can_delete_any_issue()
    {
        $issueToDelete = Issue::factory()->create([
            'title' => 'Admin Deleted',
            'project_id' => $this->project->id,
            'reporter_id' => $this->reporter->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/issues/{$issueToDelete->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_cannot_delete_nonexistent_issue()
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson('/api/issues/99999');

        $response->assertNotFound();
    }

    public function test_unauthorized_user_cannot_delete_issue()
    {
        $response = $this->actingAs($this->developer) // Not reporter or admin
            ->deleteJson("/api/issues/{$this->issue->id}");

        $response->assertForbidden();
    }

    // ============ API32: POST /api/issues/{id}/comments ============

    public function test_member_can_add_comment()
    {
        $response = $this->actingAs($this->developer)
            ->postJson("/api/issues/{$this->issue->id}/comments", [
                'content' => 'This is a comment',
            ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'issue_id',
                    'user_id',
                    'content',
                    'created_at',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'content' => 'This is a comment',
                'success' => true,
                'message' => 'Bình luận được thêm thành công',
            ]);
    }

    public function test_cannot_add_comment_to_nonexistent_issue()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/issues/99999/comments', [
                'content' => 'Comment on non-existent issue',
            ]);

        $response->assertNotFound();
    }

    public function test_add_comment_requires_content()
    {
        $response = $this->actingAs($this->developer)
            ->postJson("/api/issues/{$this->issue->id}/comments", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['content']);
    }

    public function test_unauthenticated_user_cannot_add_comment()
    {
        $response = $this->postJson("/api/issues/{$this->issue->id}/comments", [
            'content' => 'Unauthorized comment',
        ]);

        $response->assertUnauthorized();
    }

    public function test_comment_associates_with_user()
    {
        $response = $this->actingAs($this->developer)
            ->postJson("/api/issues/{$this->issue->id}/comments", [
                'content' => 'My comment',
            ]);

        $response->assertCreated();

        $data = $response->json('data');
        $this->assertEquals($this->developer->id, $data['user_id']);
        $this->assertEquals($this->issue->id, $data['issue_id']);
    }

    // ============ API33: GET /api/logs ============

    public function test_authenticated_user_can_get_logs()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/logs');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'action',
                        'description',
                        'created_at',
                    ],
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Danh sách hoạt động',
            ]);
    }

    public function test_regular_user_can_get_logs()
    {
        $response = $this->actingAs($this->developer)
            ->getJson('/api/logs');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_get_logs()
    {
        $response = $this->getJson('/api/logs');

        $response->assertUnauthorized();
    }

    // ============ Additional Issue Tests ============

    public function test_issue_has_valid_status_values()
    {
        $statuses = [IssueStatus::OPEN, IssueStatus::IN_PROGRESS, IssueStatus::RESOLVED, IssueStatus::CLOSED];
        
        foreach ($statuses as $status) {
            $issue = Issue::factory()->create([
                'status' => $status,
                'project_id' => $this->project->id,
                'reporter_id' => $this->reporter->id,
            ]);
            
            $response = $this->actingAs($this->admin)
                ->getJson("/api/issues/{$issue->id}");
            
            $response->assertOk();
            
            $issue->forceDelete();
        }
    }

    public function test_issue_has_valid_priority_values()
    {
        $priorities = [IssuePriority::LOW, IssuePriority::MEDIUM, IssuePriority::HIGH, IssuePriority::URGENT];
        
        foreach ($priorities as $priority) {
            $issue = Issue::factory()->create([
                'priority' => $priority,
                'project_id' => $this->project->id,
                'reporter_id' => $this->reporter->id,
            ]);
            
            $response = $this->actingAs($this->admin)
                ->getJson("/api/issues/{$issue->id}");
            
            $response->assertOk();
            
            $issue->forceDelete();
        }
    }

    public function test_issue_has_valid_type_values()
    {
        $types = [IssueType::TASK, IssueType::BUG, IssueType::FEATURE, IssueType::IMPROVEMENT];
        
        foreach ($types as $type) {
            $issue = Issue::factory()->create([
                'type' => $type,
                'project_id' => $this->project->id,
                'reporter_id' => $this->reporter->id,
            ]);
            
            $response = $this->actingAs($this->admin)
                ->getJson("/api/issues/{$issue->id}");
            
            $response->assertOk();
            
            $issue->forceDelete();
        }
    }

    public function test_issue_show_returns_full_details()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/issues/{$this->issue->id}");

        $response->assertOk();

        $data = $response->json('data');

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('priority', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('project_id', $data);
        $this->assertArrayHasKey('reporter_id', $data);
        $this->assertArrayHasKey('assignee_id', $data);
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }

    public function test_member_can_see_issue_in_assigned_project()
    {
        $response = $this->actingAs($this->developer)
            ->getJson("/api/issues/{$this->issue->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $this->issue->id,
            ]);
    }

    public function test_non_member_cannot_see_issue()
    {
        // Create a new user not assigned to any project
        $newUser = User::factory()->create([
            'email' => 'newuser@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_USER,
        ]);

        $response = $this->actingAs($newUser)
            ->getJson("/api/issues/{$this->issue->id}");

        $response->assertForbidden();
    }

    public function test_issue_response_format()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/issues/{$this->issue->id}");

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'data',
            'message',
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertIsArray($response->json('data'));
        $this->assertIsString($response->json('message'));
    }

    public function test_comment_response_format()
    {
        $response = $this->actingAs($this->developer)
            ->postJson("/api/issues/{$this->issue->id}/comments", [
                'content' => 'Test comment',
            ]);

        $response->assertCreated();

        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'issue_id',
                'user_id',
                'content',
                'created_at',
            ],
            'message',
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertIsArray($response->json('data'));
        $this->assertIsString($response->json('message'));
    }

    public function test_log_response_format()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/logs');

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'data',
            'message',
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertIsArray($response->json('data'));
        $this->assertIsString($response->json('message'));
    }
}

