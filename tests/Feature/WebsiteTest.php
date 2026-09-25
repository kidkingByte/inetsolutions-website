<?php

namespace Tests\Feature;

use App\Models\CoverageArea;
use App\Models\Enquiry;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\WebsiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    public static function publicPages(): array
    {
        return [
            ['/'], ['/about'], ['/internet'], ['/internet?type=business'], ['/solutions'], ['/packages'],
            ['/coverage'], ['/support'], ['/support/report-problem'], ['/get-connected'], ['/contact'],
            ['/app'], ['/faq'], ['/speed-test'], ['/network-status'], ['/news'],
            ['/legal/privacy-policy'], ['/legal/terms-and-conditions'], ['/legal/acceptable-use-policy'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_render(string $url): void
    {
        $this->seed(WebsiteSeeder::class);

        $this->get($url)->assertOk();
    }

    public function test_each_page_has_its_own_title(): void
    {
        $this->seed(WebsiteSeeder::class);

        $this->get('/contact')->assertSee('<title>Contact Us — INET SOLUTIONS LTD</title>', false);
    }

    public function test_connection_request_ignores_uploaded_files(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $this->post('/get-connected', [
            'full_name' => 'Asha', 'phone' => '0700000000', 'region' => 'Dar es Salaam',
            'attachment' => UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;'),
        ])->assertSessionHasNoErrors();

        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertSame([], Storage::disk('local')->allFiles());
        $this->assertNull(Enquiry::first()->attachment);
    }

    public function test_support_attachments_are_private_and_admin_only(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $this->post('/support/report-problem', [
            'full_name' => 'Asha', 'phone' => '0700000000', 'problem_type' => 'No Internet', 'message' => 'Down',
            'attachment' => UploadedFile::fake()->image('router.jpg'),
        ])->assertSessionHasNoErrors();

        $enquiry = Enquiry::first();
        $this->assertSame([], Storage::disk('public')->allFiles());
        Storage::disk('local')->assertExists($enquiry->attachment);

        $this->get(route('admin.enquiries.attachment', $enquiry))->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get(route('admin.enquiries.attachment', $enquiry))->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.enquiries.attachment', $enquiry))->assertOk();
    }

    public function test_support_rejects_executable_attachments(): void
    {
        $this->post('/support/report-problem', [
            'full_name' => 'Asha', 'phone' => '0700000000', 'problem_type' => 'Other', 'message' => 'x',
            'attachment' => UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;'),
        ])->assertSessionHasErrors('attachment');
    }

    public function test_public_forms_are_rate_limited(): void
    {
        $payload = ['full_name' => 'A', 'phone' => '1', 'subject' => 'S', 'message' => 'M'];

        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $payload)->assertRedirect();
        }

        $this->post('/contact', $payload)->assertStatus(429);
        $this->assertSame(5, Enquiry::count());
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'X', 'email' => 'x@example.com', 'password' => 'password', 'password_confirmation' => 'password',
        ])->assertNotFound();
        $this->assertSame(0, User::count());
    }

    public function test_seeded_admin_does_not_use_default_password(): void
    {
        $this->seed(WebsiteSeeder::class);

        $this->post('/login', ['email' => 'admin@inetsolutions.co.tz', 'password' => 'password']);

        $this->assertGuest();
    }

    public function test_posts_with_duplicate_titles_get_unique_stable_slugs(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/posts', ['title' => 'Hello'])->assertRedirect();
        $this->actingAs($admin)->post('/admin/posts', ['title' => 'Hello'])->assertRedirect();

        $this->assertSame(['hello', 'hello-2'], Post::orderBy('id')->pluck('slug')->all());

        $post = Post::first();
        $this->actingAs($admin)->put(route('admin.posts.update', $post), ['title' => 'Renamed'])->assertRedirect();
        $this->assertSame('hello', $post->fresh()->slug);
    }

    public function test_region_only_coverage_check_does_not_overclaim(): void
    {
        CoverageArea::create(['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Mikocheni', 'status' => 'available']);
        CoverageArea::create(['region' => 'Dar es Salaam', 'district' => 'Temeke', 'ward' => 'Kigamboni', 'status' => 'coming_soon']);

        $this->postJson('/coverage/check', ['region' => 'dar es salaam'])->assertJson(['status' => 'partial']);
        $this->postJson('/coverage/check', ['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Mikocheni'])
            ->assertJson(['status' => 'available']);
        $this->postJson('/coverage/check', ['region' => 'Dar es Salaam', 'district' => 'Temeke'])
            ->assertJson(['status' => 'coming_soon']);
        $this->postJson('/coverage/check', ['region' => 'Mwanza'])->assertJson(['status' => 'not_available']);
    }

    public function test_coverage_page_offers_location_dropdowns_from_recorded_areas(): void
    {
        CoverageArea::create(['region' => 'dar es salaam', 'district' => 'Kinondoni', 'ward' => 'Mikocheni', 'status' => 'available']);
        CoverageArea::create(['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Masaki', 'status' => 'available']);

        $this->assertSame(['Dar es Salaam' => ['Kinondoni' => ['Masaki', 'Mikocheni']]], CoverageArea::locationTree());

        $this->get('/coverage')
            ->assertOk()
            ->assertSee('<optgroup label="Where we operate">', false)
            ->assertSee('Mjini Magharibi')
            ->assertSee('Mikocheni');
    }

    public function test_coverage_check_returns_renderable_result_with_prefilled_connect_link(): void
    {
        CoverageArea::create(['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Mikocheni', 'status' => 'available']);

        $response = $this->postJson('/coverage/check', ['region' => 'Dar es Salaam', 'district' => 'Kinondoni', 'ward' => 'Mikocheni'])
            ->assertJson(['status' => 'available']);

        $this->assertStringContainsString('Mikocheni, Kinondoni, Dar es Salaam', $response->json('html'));
        $this->assertStringContainsString('ward=Mikocheni', $response->json('html'));

        $this->get('/get-connected?region=Dar+es+Salaam&ward=Mikocheni')->assertSee('value="Mikocheni"', false);
    }

    public function test_seeded_site_is_based_in_zanzibar_and_serves_unguja(): void
    {
        $this->seed(WebsiteSeeder::class);

        $this->assertSame(['Kaskazini Unguja', 'Kusini Unguja', 'Mjini Magharibi'], array_keys(CoverageArea::locationTree()));
        $this->postJson('/coverage/check', ['region' => 'Mjini Magharibi', 'district' => 'Mjini', 'ward' => 'Shangani'])
            ->assertJson(['status' => 'available']);
        $this->postJson('/coverage/check', ['region' => 'Dar es Salaam'])->assertJson(['status' => 'not_available']);

        $this->get('/contact')->assertSee('Zanzibar, Tanzania')->assertDontSee('Dar es Salaam, Tanzania');
    }

    public function test_reseeding_keeps_settings_changed_by_admins(): void
    {
        $this->seed(WebsiteSeeder::class);
        SiteSetting::where('key', 'phone')->update(['value' => '+255 700 000 000']);

        $this->seed(WebsiteSeeder::class);

        $this->assertSame('+255 700 000 000', SiteSetting::where('key', 'phone')->value('value'));
    }

    public function test_coverage_checks_allow_more_attempts_than_other_forms(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->postJson('/coverage/check', ['region' => 'Mwanza'])->assertOk();
        }

        $this->postJson('/coverage/check', ['region' => 'Mwanza'])->assertStatus(429);
    }
}
