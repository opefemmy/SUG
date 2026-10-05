<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\HomePageSettingsSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class SUGPortalEndToEndTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_database_has_the_receipt_schema_used_by_the_application(): void
    {
        $this->assertTrue(Schema::hasTable('receipts'));
        $this->assertTrue(Schema::hasColumn('receipts', 'receipt_no'));
        $this->assertFalse(Schema::hasColumn('receipts', 'receipt_number'));
    }

    public function test_seeded_admin_role_has_all_permissions(): void
    {
        $this->seed(PermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->assertTrue($admin->can('manage users'));
        $this->assertTrue($admin->can('view payments'));
    }

    public function test_seeded_homepage_loads_successfully(): void
    {
        $this->seed(HomePageSettingsSeeder::class);

        $this->get(route('home'))->assertOk();
    }

    public function test_admin_registration_creates_a_user_and_role(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this->post(route('register'), [
            'name' => 'Test Admin',
            'email' => 'admin-registration@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', ['email' => 'admin-registration@example.test']);
        $this->assertDatabaseHas('model_has_roles', ['model_type' => User::class]);
    }

    public function test_student_registration_creates_required_records(): void
    {
        $this->seed(PermissionSeeder::class);
        $school = \App\Models\School::create(['name' => 'Test School', 'slug' => 'test-school', 'code' => 'TS']);
        $department = \App\Models\Department::create(['school_id' => $school->id, 'name' => 'Test Department', 'slug' => 'test-department', 'code' => 'TD']);
        $programme = \App\Models\Programme::create(['department_id' => $department->id, 'name' => 'Test Programme', 'duration_years' => 4, 'code' => 'TP']);
        $level = \App\Models\Level::create(['programme_id' => $programme->id, 'level_number' => 100]);
        $session = \App\Models\AcademicSession::create(['session_name' => '2026/2027', 'is_current' => true]);

        $response = $this->post(route('register'), [
            'name' => 'Test Student',
            'email' => 'student-registration@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'matric_no' => 'TEST/001',
            'school_id' => $school->id,
            'department_id' => $department->id,
            'programme_id' => $programme->id,
            'level_id' => $level->id,
            'session_id' => $session->id,
            'admission_year' => 2026,
        ]);

        $errors = $response->getSession()->get('errors');
        $message = $errors ? json_encode($errors->getBag('default')->all()) : 'no session errors';
        $this->assertSame(route('login'), $response->headers->get('Location'), $message);

        $this->assertDatabaseHas('students', ['matric_no' => 'TEST/001']);
        $this->assertDatabaseHas('student_biodata', ['email' => 'student-registration@example.test']);
    }
}
