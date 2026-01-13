<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
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
    }

    // ============ API12: GET /api/roles ============

    public function test_authenticated_user_can_list_roles()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/roles');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
            ]);

        // Verify predefined roles exist
        $data = $response->json('data');
        $roleNames = collect($data)->pluck('name')->toArray();
        
        $this->assertContains('admin', $roleNames);
        $this->assertContains('pm', $roleNames);
        $this->assertContains('developer', $roleNames);
        $this->assertContains('qa', $roleNames);
        $this->assertContains('user', $roleNames);
    }

    public function test_regular_user_can_list_roles()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/roles');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_list_roles()
    {
        $response = $this->getJson('/api/roles');

        $response->assertUnauthorized();
    }

    // ============ API13: POST /api/roles ============

    public function test_admin_can_create_role()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/roles', [
                'name' => 'new_role',
                'description' => 'A new custom role',
            ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'name' => 'new_role',
                'description' => 'A new custom role',
                'success' => true,
            ])
            ->assertJsonFragment([
                'message' => 'Vai trò được tạo thành công',
            ]);
    }

    public function test_regular_user_cannot_create_role()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/roles', [
                'name' => 'unauthorized_role',
                'description' => 'Should fail',
            ]);

        $response->assertForbidden();
    }

    public function test_create_role_requires_name()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/roles', [
                'description' => 'Role without name',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_create_role_name_must_be_string()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/roles', [
                'name' => 12345,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    // ============ API14: PUT /api/roles/{id} ============

    public function test_admin_can_update_role()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/roles/1', [
                'name' => 'updated_admin',
                'description' => 'Updated admin role description',
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Cập nhật vai trò thành công',
            ]);
    }

    public function test_regular_user_cannot_update_role()
    {
        $response = $this->actingAs($this->user)
            ->putJson('/api/roles/1', [
                'name' => 'hacked_role',
            ]);

        $response->assertForbidden();
    }

    public function test_update_role_with_empty_name()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/roles/1', [
                'name' => '',
            ]);

        $response->assertUnprocessable();
    }

    public function test_update_role_with_partial_data()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/roles/1', [
                'description' => 'Only description updated',
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    // ============ API15: DELETE /api/roles/{id} ============

    public function test_admin_can_delete_role()
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson('/api/roles/1');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Xóa vai trò thành công',
            ]);
    }

    public function test_regular_user_cannot_delete_role()
    {
        $response = $this->actingAs($this->user)
            ->deleteJson('/api/roles/1');

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_cannot_delete_role()
    {
        $response = $this->deleteJson('/api/roles/1');

        $response->assertUnauthorized();
    }

    public function test_delete_nonexistent_role()
    {
        $response = $this->actingAs($this->admin)
            ->deleteJson('/api/roles/99999');

        $response->assertOk(); // Controller doesn't check existence
    }

    // ============ Additional Role Tests ============

    public function test_roles_have_valid_structure()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/roles');

        $response->assertOk();

        $data = $response->json('data');
        
        foreach ($data as $role) {
            $this->assertArrayHasKey('id', $role);
            $this->assertArrayHasKey('name', $role);
            $this->assertArrayHasKey('description', $role);
            $this->assertIsInt($role['id']);
            $this->assertIsString($role['name']);
            $this->assertIsString($role['description']);
        }
    }

    public function test_roles_response_contains_all_predefined_roles()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/roles');

        $response->assertOk();

        $data = $response->json('data');
        $this->assertCount(5, $data);
    }

    public function test_create_role_returns_valid_id()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/roles', [
                'name' => 'test_role_' . time(),
                'description' => 'Test role',
            ]);

        $response->assertCreated();

        $data = $response->json('data');
        $this->assertArrayHasKey('id', $data);
        $this->assertNotEmpty($data['id']);
    }

    public function test_role_permissions_are_consistent()
    {
        // Admin should have full access
        $this->actingAs($this->admin)
            ->getJson('/api/roles')
            ->assertOk();

        $this->actingAs($this->admin)
            ->postJson('/api/roles', ['name' => 'test'])
            ->assertCreated();

        $this->actingAs($this->admin)
            ->putJson('/api/roles/1', ['name' => 'updated'])
            ->assertOk();

        $this->actingAs($this->admin)
            ->deleteJson('/api/roles/2')
            ->assertOk();

        // Regular user should be denied
        $this->actingAs($this->user)
            ->postJson('/api/roles', ['name' => 'test'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->putJson('/api/roles/1', ['name' => 'updated'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->deleteJson('/api/roles/2')
            ->assertForbidden();
    }
}

