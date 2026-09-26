<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\CoverageArea;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\User;
use Database\Seeders\WebsiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function staff(string $role, array $attributes = []): User
    {
        return User::factory()->create(['role' => $role] + $attributes);
    }

    protected function lead(array $attributes = []): Enquiry
    {
        return Enquiry::create($attributes + ['type' => 'connection_request', 'full_name' => 'Asha Juma', 'phone' => '0712345678', 'region' => 'Mjini Magharibi']);
    }

    protected function ticket(array $attributes = []): Enquiry
    {
        return Enquiry::create($attributes + ['type' => 'support_request', 'full_name' => 'Juma Ali', 'phone' => '0777000000', 'problem_type' => 'No Internet', 'message' => 'Down']);
    }

    public static function sectionAccess(): array
    {
        // role => [route => expected status]
        return [
            'sales' => ['sales', ['admin.dashboard' => 200, 'admin.enquiries.index' => 200, 'admin.packages.index' => 403, 'admin.posts.index' => 403, 'admin.settings.edit' => 403, 'admin.users.index' => 403, 'admin.audit.index' => 403]],
            'support' => ['support', ['admin.dashboard' => 200, 'admin.enquiries.index' => 200, 'admin.network-status.index' => 200, 'admin.coverage.index' => 403, 'admin.users.index' => 403]],
            'content' => ['content', ['admin.dashboard' => 200, 'admin.enquiries.index' => 403, 'admin.posts.index' => 200, 'admin.promotions.index' => 200, 'admin.packages.index' => 403]],
            'manager' => ['manager', ['admin.packages.index' => 200, 'admin.coverage.index' => 200, 'admin.audit.index' => 200, 'admin.users.index' => 403, 'admin.settings.edit' => 403]],
            'admin' => ['admin', ['admin.users.index' => 200, 'admin.settings.edit' => 200, 'admin.audit.index' => 200]],
        ];
    }

    #[DataProvider('sectionAccess')]
    public function test_each_role_only_reaches_its_sections(string $role, array $expectations): void
    {
        $this->seed(WebsiteSeeder::class);
        $user = $this->staff($role);

        foreach ($expectations as $route => $status) {
            $this->actingAs($user)->get(route($route))->assertStatus($status);
        }
    }

    public function test_customers_and_deactivated_staff_are_kept_out(): void
    {
        $this->actingAs($this->staff('customer'))->get(route('admin.dashboard'))->assertForbidden();

        $this->actingAs($this->staff('sales', ['is_active' => false]))->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_deactivated_staff_cannot_sign_in(): void
    {
        $this->staff('sales', ['email' => 'off@inet.test', 'is_active' => false]);

        $this->post('/login', ['email' => 'off@inet.test', 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_sales_see_leads_but_not_support_tickets(): void
    {
        $lead = $this->lead();
        $ticket = $this->ticket();
        $sales = $this->staff('sales');

        $this->actingAs($sales)->get(route('admin.enquiries.index'))->assertSee('Asha Juma')->assertDontSee('Juma Ali');
        $this->actingAs($sales)->get(route('admin.enquiries.show', $lead))->assertOk();
        $this->actingAs($sales)->get(route('admin.enquiries.show', $ticket))->assertForbidden();
        $this->actingAs($sales)->put(route('admin.enquiries.update', $ticket), ['status' => 'closed'])->assertForbidden();
        $this->assertSame('new', $ticket->fresh()->status);
    }

    public function test_only_managers_and_admins_delete_enquiries(): void
    {
        $lead = $this->lead();

        $this->actingAs($this->staff('sales'))->delete(route('admin.enquiries.destroy', $lead))->assertForbidden();
        $this->actingAs($this->staff('manager'))->delete(route('admin.enquiries.destroy', $lead))->assertRedirect();
        $this->assertSoftDeleted($lead);
    }

    public function test_leads_can_only_be_assigned_to_staff_who_may_work_them(): void
    {
        $lead = $this->lead();
        $manager = $this->staff('manager');
        $sales = $this->staff('sales');
        $support = $this->staff('support');

        $this->actingAs($manager)->put(route('admin.enquiries.update', $lead), ['assigned_to' => $support->id])->assertSessionHasErrors('assigned_to');
        $this->actingAs($manager)->put(route('admin.enquiries.update', $lead), ['assigned_to' => $sales->id, 'status' => 'contacted'])->assertSessionHasNoErrors();

        $this->assertSame($sales->id, $lead->fresh()->assigned_to);
        $this->actingAs($sales)->get(route('admin.enquiries.index', ['assigned' => 'me']))->assertSee('Asha Juma');
    }

    public function test_staff_changes_are_written_to_the_audit_log(): void
    {
        $lead = $this->lead();
        $this->assertSame(0, AuditLog::count(), 'website submissions are not audited');

        $manager = $this->staff('manager');
        $this->actingAs($manager)->put(route('admin.enquiries.update', $lead), ['status' => 'qualified']);

        $log = AuditLog::where('event', 'updated')->firstOrFail();
        $this->assertTrue($log->user->is($manager));
        $this->assertTrue($log->auditable->is($lead));
        $this->assertSame(['old' => 'new', 'new' => 'qualified'], $log->changes['status']);

        $this->actingAs($manager)->get(route('admin.enquiries.show', $lead))->assertSee('qualified');
    }

    public function test_package_edits_and_sign_ins_are_audited_without_passwords(): void
    {
        $admin = $this->staff('admin', ['email' => 'boss@inet.test']);
        $package = Package::create(['name' => 'Home Basic', 'category' => 'home']);

        $this->post('/login', ['email' => 'boss@inet.test', 'password' => 'password']);
        $this->assertDatabaseHas('audit_logs', ['event' => 'login', 'user_id' => $admin->id]);
        $this->assertNotNull($admin->fresh()->last_login_at);

        $this->put(route('admin.packages.update', $package), ['name' => 'Home Basic+', 'category' => 'home']);
        $this->assertDatabaseHas('audit_logs', ['event' => 'updated', 'auditable_type' => Package::class, 'auditable_id' => $package->id]);

        $this->put(route('admin.users.update', $admin), ['name' => 'Boss', 'email' => 'boss@inet.test', 'role' => 'admin', 'is_active' => 1, 'password' => 'n3w-Password!']);
        $userLog = AuditLog::where('auditable_type', User::class)->where('event', 'updated')->latest('id')->firstOrFail();
        $this->assertArrayNotHasKey('password', $userLog->changes);
    }

    public function test_admin_creates_staff_with_a_one_time_temporary_password(): void
    {
        $admin = $this->staff('admin');

        $response = $this->actingAs($admin)->post(route('admin.users.store'), ['name' => 'New Agent', 'email' => 'agent@inet.test', 'role' => 'sales', 'is_active' => 1]);

        $response->assertRedirect(route('admin.users.index'))->assertSessionHas('status', fn ($m) => str_contains($m, 'Temporary password'));
        $agent = User::where('email', 'agent@inet.test')->firstOrFail();
        $this->assertSame('sales', $agent->role);
        $this->assertTrue($agent->can('leads.view'));
        $this->assertFalse($agent->can('users.manage'));
    }

    public function test_admins_cannot_lock_themselves_out(): void
    {
        $admin = $this->staff('admin');

        $this->actingAs($admin)->put(route('admin.users.update', $admin), ['name' => $admin->name, 'email' => $admin->email, 'role' => 'sales', 'is_active' => 1])
            ->assertSessionHasErrors('role');
        $this->actingAs($admin)->put(route('admin.users.update', $admin), ['name' => $admin->name, 'email' => $admin->email, 'role' => 'admin', 'is_active' => 0])
            ->assertSessionHasErrors('role');
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('user');

        $this->assertTrue($admin->fresh()->isStaff());
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_an_admin_can_deactivate_another_admin_and_that_admin_loses_access(): void
    {
        $admin = $this->staff('admin');
        $other = $this->staff('admin');

        $this->actingAs($admin)->put(route('admin.users.update', $other), ['name' => $other->name, 'email' => $other->email, 'role' => 'admin', 'is_active' => 0])
            ->assertSessionHasNoErrors();

        $this->actingAs($other->fresh())->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_the_user_screen_only_manages_staff_accounts(): void
    {
        $customer = $this->staff('customer');

        $this->actingAs($this->staff('admin'))->get(route('admin.users.edit', $customer))->assertNotFound();
    }

    public function test_csv_export_is_scoped_logged_and_formula_safe(): void
    {
        $this->lead(['full_name' => '=HYPERLINK("http://evil")']);
        $this->ticket();
        $sales = $this->staff('sales');

        $response = $this->actingAs($sales)->get(route('admin.enquiries.export'));
        $csv = $response->assertOk()->streamedContent();

        $this->assertStringContainsString("'=HYPERLINK", $csv);
        $this->assertStringNotContainsString('Juma Ali', $csv, 'support tickets are not exported to sales');
        $this->assertDatabaseHas('audit_logs', ['event' => 'exported', 'user_id' => $sales->id]);

        $this->actingAs($this->staff('support'))->get(route('admin.enquiries.export'))->assertForbidden();
    }

    public function test_dashboard_shows_overdue_enquiries_and_role_specific_kpis(): void
    {
        $this->lead(['created_at' => now()->subDays(2)]);
        $this->ticket();

        $this->actingAs($this->staff('sales'))->get(route('admin.dashboard'))
            ->assertSee('Needs attention')->assertSee('Asha Juma')->assertSee('New leads')->assertDontSee('Open support tickets');

        $this->actingAs($this->staff('content'))->get(route('admin.dashboard'))
            ->assertDontSee('Needs attention')->assertSee('Published posts');
    }

    public function test_coverage_areas_can_have_an_exact_map_position(): void
    {
        $manager = $this->staff('manager');

        $this->actingAs($manager)->post(route('admin.coverage.store'), ['region' => 'kusini pemba', 'district' => 'Chake Chake', 'status' => 'available', 'latitude' => '-5.2459', 'longitude' => '39.7666'])
            ->assertSessionHasNoErrors();
        $area = CoverageArea::firstOrFail();
        $this->assertSame('Kusini Pemba', $area->region);
        $this->assertSame([-5.2459, 39.7666], [$area->latitude, $area->longitude]);

        // Both or neither, and within range
        $this->actingAs($manager)->post(route('admin.coverage.store'), ['region' => 'Kusini Pemba', 'status' => 'available', 'latitude' => '-5.2'])
            ->assertSessionHasErrors('longitude');
        $this->actingAs($manager)->post(route('admin.coverage.store'), ['region' => 'Kusini Pemba', 'status' => 'available', 'latitude' => '-95', 'longitude' => '39'])
            ->assertSessionHasErrors('latitude');
    }

    public function test_sidebar_only_lists_permitted_sections(): void
    {
        $this->actingAs($this->staff('support'))->get(route('admin.dashboard'))
            ->assertSee('Support tickets')->assertSee('Network status')
            ->assertDontSee('Sales leads')->assertDontSee('Staff &amp; roles', false)->assertDontSee('Audit log');
    }
}
