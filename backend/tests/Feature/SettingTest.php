<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
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

    // ============ API23: GET /api/settings ============

    public function test_authenticated_user_can_get_settings()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/settings');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'app_name',
                    'app_timezone',
                    'date_format',
                    'time_format',
                    'language',
                    'work_days',
                    'work_hours_per_day',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Cài đặt hệ thống',
            ]);
    }

    public function test_regular_user_can_get_settings()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/settings');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_get_settings()
    {
        $response = $this->getJson('/api/settings');

        $response->assertUnauthorized();
    }

    public function test_settings_have_default_values()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/settings');

        $response->assertOk();

        $data = $response->json('data');

        $this->assertEquals('Project Management System', $data['app_name']);
        $this->assertEquals('Asia/Ho_Chi_Minh', $data['app_timezone']);
        $this->assertEquals('DD/MM/YYYY', $data['date_format']);
        $this->assertEquals('HH:mm', $data['time_format']);
        $this->assertEquals('vi', $data['language']);
        $this->assertEquals(5, $data['work_days']);
        $this->assertEquals(8, $data['work_hours_per_day']);
    }

    public function test_settings_has_all_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/settings');

        $response->assertOk();

        $data = $response->json('data');

        $requiredFields = [
            'app_name',
            'app_timezone',
            'date_format',
            'time_format',
            'language',
            'work_days',
            'work_hours_per_day',
        ];

        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $data);
        }
    }

    // ============ API24: PUT /api/settings ============

    public function test_admin_can_update_settings()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/settings', [
                'app_name' => 'Updated PMS',
                'app_timezone' => 'Asia/Tokyo',
                'date_format' => 'YYYY-MM-DD',
                'language' => 'en',
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data',
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Cập nhật cài đặt thành công',
            ]);

        // Verify settings are updated
        $data = $response->json('data');
        $this->assertEquals('Updated PMS', $data['app_name']);
    }

    public function test_regular_user_cannot_update_settings()
    {
        $response = $this->actingAs($this->user)
            ->putJson('/api/settings', [
                'app_name' => 'Hacked PMS',
            ]);

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_cannot_update_settings()
    {
        $response = $this->putJson('/api/settings', [
            'app_name' => 'Unauthorized Update',
        ]);

        $response->assertUnauthorized();
    }

    public function test_update_settings_validates_work_days_as_integer()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/settings', [
                'work_days' => 'invalid',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['work_days']);
    }

    public function test_update_settings_validates_work_hours_as_integer()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/settings', [
                'work_hours_per_day' => 'not-a-number',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['work_hours_per_day']);
    }

    public function test_update_settings_with_empty_data()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/settings', []);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_update_settings_merges_with_defaults()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/settings', [
                'app_name' => 'Custom Name',
            ]);

        $response->assertOk();

        $data = $response->json('data');

        // Other fields should remain at default
        $this->assertEquals('Custom Name', $data['app_name']);
        $this->assertEquals('Asia/Ho_Chi_Minh', $data['app_timezone']);
        $this->assertEquals('DD/MM/YYYY', $data['date_format']);
    }

    // ============ API25: GET /api/schedules ============

    public function test_authenticated_user_can_get_schedule()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/schedules');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'work_days',
                    'work_hours_per_day',
                    'start_time',
                    'end_time',
                    'break_time',
                ],
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Thông tin lịch làm việc',
            ]);
    }

    public function test_regular_user_can_get_schedule()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/schedules');

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_unauthenticated_user_cannot_get_schedule()
    {
        $response = $this->getJson('/api/schedules');

        $response->assertUnauthorized();
    }

    public function test_schedule_has_default_work_days()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/schedules');

        $response->assertOk();

        $data = $response->json('data');

        $this->assertIsArray($data['work_days']);
        $this->assertContains('Monday', $data['work_days']);
        $this->assertContains('Tuesday', $data['work_days']);
        $this->assertContains('Wednesday', $data['work_days']);
        $this->assertContains('Thursday', $data['work_days']);
        $this->assertContains('Friday', $data['work_days']);
        $this->assertCount(5, $data['work_days']);
    }

    public function test_schedule_has_default_hours()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/schedules');

        $response->assertOk();

        $data = $response->json('data');

        $this->assertEquals(8, $data['work_hours_per_day']);
        $this->assertEquals('08:00', $data['start_time']);
        $this->assertEquals('17:00', $data['end_time']);
    }

    public function test_schedule_has_break_time()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/schedules');

        $response->assertOk();

        $data = $response->json('data');

        $this->assertIsArray($data['break_time']);
        $this->assertContains('12:00', $data['break_time']);
        $this->assertContains('13:00', $data['break_time']);
    }

    // ============ API26: PUT /api/schedules ============

    public function test_admin_can_update_schedule()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/schedules', [
                'work_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'work_hours_per_day' => 9,
                'start_time' => '09:00',
                'end_time' => '18:00',
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data',
                'message',
            ])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Cập nhật lịch làm việc thành công',
            ]);

        $data = $response->json('data');
        $this->assertEquals(9, $data['work_hours_per_day']);
        $this->assertEquals('09:00', $data['start_time']);
    }

    public function test_regular_user_cannot_update_schedule()
    {
        $response = $this->actingAs($this->user)
            ->putJson('/api/schedules', [
                'work_hours_per_day' => 1,
            ]);

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_cannot_update_schedule()
    {
        $response = $this->putJson('/api/schedules', [
            'work_hours_per_day' => 1,
        ]);

        $response->assertUnauthorized();
    }

    public function test_update_schedule_validates_work_days_as_array()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/schedules', [
                'work_days' => 'not-an-array',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['work_days']);
    }

    public function test_update_schedule_validates_work_hours_as_integer()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/schedules', [
                'work_hours_per_day' => 'ten',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['work_hours_per_day']);
    }

    public function test_update_schedule_with_empty_data()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/schedules', []);

        $response->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);
    }

    public function test_update_schedule_merges_with_defaults()
    {
        $response = $this->actingAs($this->admin)
            ->putJson('/api/schedules', [
                'start_time' => '10:00',
            ]);

        $response->assertOk();

        $data = $response->json('data');

        // Only start_time should be updated
        $this->assertEquals('10:00', $data['start_time']);
        $this->assertEquals(8, $data['work_hours_per_day']); // Default
    }

    // ============ Additional Settings Tests ============

    public function test_settings_response_format()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/settings');

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

    public function test_schedule_response_format()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/schedules');

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

    public function test_settings_and_schedule_are_independent()
    {
        // Update settings
        $settingsResponse = $this->actingAs($this->admin)
            ->putJson('/api/settings', [
                'work_days' => 6,
            ]);

        $this->assertTrue($settingsResponse->json('success'));

        // Update schedule
        $scheduleResponse = $this->actingAs($this->admin)
            ->putJson('/api/schedules', [
                'work_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            ]);

        $this->assertTrue($scheduleResponse->json('success'));

        // Verify they are separate
        $settingsData = $settingsResponse->json('data');
        $scheduleData = $scheduleResponse->json('data');

        // work_days in settings is an integer (number of days)
        $this->assertIsInt($settingsData['work_days']);

        // work_days in schedule is an array of day names
        $this->assertIsArray($scheduleData['work_days']);
    }

    public function test_admin_has_full_access_to_settings()
    {
        // Admin should be able to do everything
        $this->actingAs($this->admin)
            ->getJson('/api/settings')
            ->assertOk();

        $this->actingAs($this->admin)
            ->putJson('/api/settings', ['app_name' => 'Test'])
            ->assertOk();

        $this->actingAs($this->admin)
            ->getJson('/api/schedules')
            ->assertOk();

        $this->actingAs($this->admin)
            ->putJson('/api/schedules', ['start_time' => '08:00'])
            ->assertOk();
    }

    public function test_user_has_readonly_access_to_settings()
    {
        // Regular user can only read
        $this->actingAs($this->user)
            ->getJson('/api/settings')
            ->assertOk();

        $this->actingAs($this->user)
            ->putJson('/api/settings', ['app_name' => 'Hacked'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->getJson('/api/schedules')
            ->assertOk();

        $this->actingAs($this->user)
            ->putJson('/api/schedules', ['start_time' => '00:00'])
            ->assertForbidden();
    }
}

