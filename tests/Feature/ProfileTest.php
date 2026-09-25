<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function staff(): User
    {
        return User::factory()->create(['role' => 'sales']);
    }

    public function test_profile_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->staff())
            ->get('/profile');

        $response->assertOk()->assertSee('Change password')->assertSee('View sales leads');
    }

    public function test_profile_is_only_for_staff(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'customer']))->get('/profile')->assertForbidden();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->staff();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = $this->staff();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_staff_cannot_delete_their_own_account(): void
    {
        $user = $this->staff();

        $this->actingAs($user)->delete('/profile', ['password' => 'password'])->assertStatus(405);

        $this->assertNotNull($user->fresh());
    }
}
