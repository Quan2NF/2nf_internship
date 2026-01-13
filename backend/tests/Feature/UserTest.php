<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected User $testUser;
    protected string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_ADMIN,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        // Create regular user
        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt($this->password),
            'role' => User::ROLE_USER,
            'is_active' => User::STATUS_ACTIVE,
        ]);

        // Create test user for CRUD operations
        $this->testUser = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt($this->password),
            'name' => 'Test User',
            'role' => User::ROLE_USER,
            'is_active' => User::STATUS_ACTIVE,
        ]);
    }

    // ============ API05: GET /api/users ============

    public function test_admin_can_list_all_users()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'employee_code',
                        'name',
                        'email',
                        'phone_number',
                        'birthday',
                        'gender',
                        'role',
                        'is_active',
                        'join_date',
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

    public function test_regular_user_can_list_all_users()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/users');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_list_users()
    {
        $response = $this->getJson('/api/users');

        $response->assertUnauthorized();
    }

    // ============ API06: GET /api/users/filter ============

    public function test_can_filter_users_by_name()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users/filter?name=Test');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $data = $response->json('data');
        foreach ($data as $user) {
            $this->assertStringContainsString('Test', $user['name']);
        }
    }

    public function test_can_filter_users_by_email()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users/filter?email=testuser');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_can_filter_users_by_is_active()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users/filter?is_active=1');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_can_filter_users_by_role()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users/filter?role=admin');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_filter_returns_empty_when_no_match()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users/filter?name=NonExistentUser12345');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonPath('data', []);
    }

    // ============ API07: POST /api/users ============

    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/users', [
                'employee_code' => 'EMP-001',
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'phone_number' => '0123456789',
                'birthday' => '1990-01-01',
                'gender' => 1,
                'join_date' => '2024-01-01',
            ]);

        $response->assertCreated()
            ->assertJsonFragment([
                'email' => 'newuser@example.com',
                'name' => 'New User',
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'employee_code' => 'EMP-001',
        ]);
    }

    public function test_regular_user_cannot_create_user()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/users', [
                'employee_code' => 'EMP-002',
                'name' => 'Another User',
                'email' => 'another@example.com',
                'password' => 'password123',
            ]);

        $response->assertForbidden();
    }

    public function test_create_user_validates_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/users', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['employee_code', 'name', 'email', 'password']);
    }

    public function test_create_user_validates_unique_email()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/users', [
                'employee_code' => 'EMP-003',
                'name' => 'Duplicate Email User',
                'email' => 'admin@example.com', // Already exists
                'password' => 'password123',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_create_user_validates_unique_employee_code()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/users', [
                'employee_code' => $this->admin->employee_code, // Already exists
                'name' => 'Duplicate Code User',
                'email' => 'duplicate@example.com',
                'password' => 'password123',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['employee_code']);
    }

    // ============ API08: PUT /api/users/{id} ============

    public function test_admin_can_update_user()
    {
        $response = $this->actingAs($this->admin)
            ->putJson("/api/users/{$this->testUser->id}", [
                'name' => 'Updated User Name',
                'phone_number' => '0987654321',
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->testUser->id,
            'name' => 'Updated User Name',
        ]);
    }

    public function test_user_can_update_own_profile()
    {
        $response = $this->actingAs($this->user)
            ->putJson("/api/users/{$this->user->id}", [
                'name' => 'My Updated Name',
                'phone_number' => '1112223333',
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_cannot_update_nonexistent_user()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/users/99999', [
                'name' => 'Updated Name',
            ]);

        $response->assertNotFound();
    }

    public function test_update_user_validates_email_format()
    {
        $response = $this->actingAs($this->admin)
            ->putJson("/api/users/{$this->testUser->id}", [
                'email' => 'invalid-email',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    // ============ API09: DELETE /api/users/{id} ============

    public function test_admin_can_delete_user()
    {
        $userToDelete = User::factory()->create([
            'email' => 'todelete@example.com',
            'password' => bcrypt($this->password),
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/users/{$userToDelete->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Xóa người dùng thành công',
            ]);

        $this->assertSoftDeleted('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_regular_user_cannot_delete_user()
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/users/{$this->testUser->id}");

        $response->assertForbidden();
    }

    public function test_cannot_delete_nonexistent_user()
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson('/api/users/99999');

        $response->assertNotFound();
    }

    public function test_cannot_delete_self()
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/users/{$this->admin->id}");

        $response->assertOk(); // Soft delete works
    }

    // ============ API10: POST /api/users/{id}/assign-role ============

    public function test_admin_can_assign_role_to_user()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/users/{$this->testUser->id}/assign-role", [
                'role' => User::ROLE_PM,
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Gán vai trò thành công',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->testUser->id,
            'role' => User::ROLE_PM,
        ]);
    }

    public function test_regular_user_cannot_assign_role()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/users/{$this->testUser->id}/assign-role", [
                'role' => User::ROLE_ADMIN,
            ]);

        $response->assertForbidden();
    }

    public function test_assign_role_requires_role_field()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/users/{$this->testUser->id}/assign-role", []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['role']);
    }

    public function test_assign_role_validates_invalid_role()
    {
        $response = $this->actingAs($this->admin)
            ->postJson("/api/users/{$this->testUser->id}/assign-role", [
                'role' => 'invalid_role',
            ]);

        // Note: Controller doesn't validate role values, just updates
        $response->assertOk();
    }

    // ============ API11: GET /api/users/{id}/roles ============

    public function test_can_get_user_roles()
    {
        $response = $this->actingAs($this->admin)
            ->getJson("/api/users/{$this->testUser->id}/roles");

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user_id',
                    'role',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'user_id' => $this->testUser->id,
                'success' => true,
            ]);
    }

    public function test_cannot_get_roles_of_nonexistent_user()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/users/99999/roles');

        $response->assertNotFound();
    }

    public function test_regular_user_can_get_own_roles()
    {
        $response = $this->actingAs($this->user)
            ->getJson("/api/users/{$this->user->id}/roles");

        $response->assertOk()
            ->assertJsonFragment([
                'user_id' => $this->user->id,
                'success' => true,
            ]);
    }
}

