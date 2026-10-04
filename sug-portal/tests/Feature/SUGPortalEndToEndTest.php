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
}
