<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Account page contract (UI-v2 Phase 5).
 *
 * Guards the profile.blade.php rebuild: settings structure, the three forms
 * keep their actions/methods/field names, the delete modal keeps its
 * confirmation contract (and auto-opens on validation error), password
 * helpers keep their behavior hooks — without legacy card/gradient/emoji
 * patterns. Behavior itself stays covered by ProfileTest.
 */
class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_renders_settings_structure_and_forms(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertOk();
        $response->assertSee('Pengaturan akun', false);
        $response->assertSee(e($user->name), false);
        $response->assertSee(e($user->email), false);
        foreach (['Profil', 'Keamanan', 'Hapus akun'] as $section) {
            $response->assertSee($section, false);
        }
        // Form contracts: actions + spoofed methods + field names
        $response->assertSee('action="'.route('profile.update').'"', false);
        $response->assertSee('name="_method" value="PATCH"', false);
        $response->assertSee('action="'.route('password.update').'"', false);
        $response->assertSee('name="_method" value="PUT"', false);
        $response->assertSee('action="'.route('profile.destroy').'"', false);
        $response->assertSee('name="_method" value="DELETE"', false);
        foreach (['name="name"', 'name="email"', 'name="current_password"', 'name="password"', 'name="password_confirmation"', 'id="confirm-password"'] as $field) {
            $response->assertSee($field, false);
        }
        // Behavior hooks for profile.js
        $response->assertSee('data-password-toggle="password"', false);
        $response->assertSee('id="strength-container"', false);
        $response->assertSee('id="delete-modal"', false);
        $response->assertSee('data-open-modal="delete-modal"', false);
        $response->assertSee('data-close-modal', false);
        // Modal starts hidden
        $response->assertSee('id="delete-modal" class="ds-modal-overlay ds-hidden"', false);
        // No legacy patterns in page content
        $main = Str::between($response->getContent(), '<main id="main-content">', '</main>');
        foreach (['glass-card', 'gradient-text', 'auth-input'] as $banned) {
            $this->assertStringNotContainsString($banned, $main);
        }
        $this->assertSame(0, preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $main));
    }

    public function test_profile_validation_errors_are_visible_inline(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '',
            'email' => 'bukan-email',
        ])->assertSessionHasErrors(['name', 'email']);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertSee('aria-invalid="true"', false);
        $response->assertSee('role="alert"', false);
    }

    public function test_delete_modal_opens_automatically_on_password_error(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'salah-sandi',
        ])->assertSessionHasErrors('password');

        $response = $this->actingAs($user)->get(route('profile'));

        // Overlay renders without ds-hidden so the error is visible
        $response->assertSee('id="delete-modal" class="ds-modal-overlay"', false);
        $response->assertDontSee('id="delete-modal" class="ds-modal-overlay ds-hidden"', false);
    }
}
