<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Authentication pages contract (UI-v2 Phase 6).
 *
 * Guards the auth/*.blade.php rebuild on the shared x-auth.card pattern:
 * form actions, CSRF, field names (incl. remember + terms), password-reset
 * token flow, verification resend/logout contracts, inline validation, and
 * the absence of legacy glow/glass/gradient/emoji patterns. Backend behavior
 * stays covered by AuthenticationTest.
 */
class AuthPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_renders_contracts(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('action="'.route('login.store').'"', false);
        $response->assertSee('_token', false);
        foreach (['name="email"', 'name="password"', 'name="remember"'] as $field) {
            $response->assertSee($field, false);
        }
        $response->assertSee(route('password.request'), false);
        $response->assertSee(route('register'), false);
        $response->assertSee('data-password-toggle="password"', false);
        $this->assertNoLegacyAuthPatterns($response->getContent());
    }

    public function test_register_renders_contracts(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('action="'.route('register.store').'"', false);
        foreach (['name="name"', 'name="email"', 'name="password"', 'name="password_confirmation"', 'name="terms"'] as $field) {
            $response->assertSee($field, false);
        }
        $response->assertSee('/kebijakan-privasi', false);
        $response->assertSee('id="register-strength-container"', false);
        $response->assertSee('id="confirm-match"', false);
        $response->assertSee(route('login'), false);
        $this->assertNoLegacyAuthPatterns($response->getContent());
    }

    public function test_register_requires_terms_acceptance(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Calon Pengguna',
            'email' => 'calon@contoh.id',
            'password' => 'Rahasia123!',
            'password_confirmation' => 'Rahasia123!',
        ])->assertSessionHasErrors('terms');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_forgot_password_renders_and_sends_link(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('action="'.route('password.email').'"', false)
            ->assertSee('name="email"', false);

        Notification::fake();
        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_renders_token_contract(): void
    {
        $response = $this->get(route('password.reset', 'contoh-token-reset'));

        $response->assertOk();
        $response->assertSee('action="'.route('password.store').'"', false);
        $response->assertSee('name="token" value="contoh-token-reset"', false);
        foreach (['name="email"', 'name="password"', 'name="password_confirmation"'] as $field) {
            $response->assertSee($field, false);
        }
        $this->assertNoLegacyAuthPatterns($response->getContent());
    }

    public function test_verify_email_renders_resend_and_account_contracts(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertOk();
        $response->assertSee(e($user->email), false);
        $response->assertSee('action="'.route('verification.send').'"', false);
        $response->assertSee(route('profile'), false);
        $response->assertSee('action="'.route('logout').'"', false);
        $this->assertNoLegacyAuthPatterns($response->getContent());
    }

    private function assertNoLegacyAuthPatterns(string $html): void
    {
        $main = Str::between($html, '<main id="main-content">', '</main>');
        foreach (['glass-card', 'glow-orb', 'gradient-text', 'auth-input', 'animate-scale-in'] as $banned) {
            $this->assertStringNotContainsString($banned, $main);
        }
        $this->assertSame(0, preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $main));
    }
}
