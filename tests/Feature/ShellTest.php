<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Application shell contract (UI-v2 Phase 2).
 *
 * The shell (navbar, mobile menu, footer, flash) must keep every
 * backend-facing contract while rendering the new light markup:
 * route hrefs, logout POST + CSRF, aria wiring, element IDs consumed
 * by resources/js/modules/navigation.js.
 */
class ShellTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_shell_renders_navigation_and_footer(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        // Navbar structure + IDs consumed by navigation.js
        $response->assertSee('id="main-navbar"', false);
        $response->assertSee('id="menu-toggle"', false);
        $response->assertSee('id="mobile-menu"', false);
        $response->assertSee('id="menu-close"', false);
        // Desktop links
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertSee('Cek Berita');
        // Footer: only real routes, no fake social buttons
        $response->assertSee(route('statistik'));
        $response->assertSee('/kebijakan-privasi');
        $response->assertDontSee('Sistem aktif');
        // No legacy shell patterns (page-level legacy stays until its phase)
        $response->assertDontSee('toggleUserMenu()');
        $response->assertDontSee('updateNav');
        $response->assertDontSee('onmouseover');
        $response->assertDontSee('backdrop-filter:blur(12px)');
    }

    public function test_authenticated_shell_renders_account_menu_with_logout_contract(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('id="user-menu-btn"', false);
        $response->assertSee('id="user-dropdown"', false);
        $response->assertSee(e($user->name), false);
        $response->assertSee(route('profile'));
        $response->assertSee(route('riwayat'));
        // Logout must stay POST with CSRF
        $response->assertSee('action="'.route('logout').'"', false);
        $response->assertSee('_token', false);
    }

    public function test_auth_pages_still_use_shared_shell(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }
}
