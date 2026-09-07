<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

/**
 * UI-v2 design foundation primitives (Phase 1).
 *
 * Guards the components/ui/* contract: every primitive must render with
 * ds-* classes and must not reintroduce banned patterns (glass, gradient
 * text, glow, emoji icons) per DESIGN_SYSTEM §4.
 */
class UiComponentsTest extends TestCase
{
    public function test_button_renders_variants(): void
    {
        $html = Blade::render('<x-ui.button variant="primary">Periksa</x-ui.button>');

        $this->assertStringContainsString('ds-btn ds-btn-primary', $html);
        $this->assertStringContainsString('<button', $html);
        $this->assertStringNotContainsString('glass-card', $html);

        $link = Blade::render('<x-ui.button variant="secondary" href="/riwayat">Riwayat</x-ui.button>');

        $this->assertStringContainsString('<a href="/riwayat"', $link);
        $this->assertStringContainsString('ds-btn-secondary', $link);
    }

    public function test_alert_renders_semantic_types(): void
    {
        foreach (['success', 'error', 'warning', 'info'] as $type) {
            $html = Blade::render("<x-ui.alert type=\"{$type}\" title=\"Judul\">Isi pesan.</x-ui.alert>");

            $this->assertStringContainsString("ds-alert-{$type}", $html, "alert type {$type}");
            $this->assertStringContainsString('<svg', $html, "alert type {$type} has icon");
        }
    }

    public function test_badge_renders_result_labels_without_emoji(): void
    {
        foreach (['valid', 'hoax', 'meragukan'] as $type) {
            $html = Blade::render("<x-ui.badge type=\"{$type}\">Label</x-ui.badge>");

            $this->assertStringContainsString("ds-badge-{$type}", $html, "badge type {$type}");
            $this->assertStringContainsString('<svg', $html, "badge type {$type} uses SVG icon");
        }
    }

    public function test_input_renders_label_hint_and_error_states(): void
    {
        $html = Blade::render('<x-ui.input label="Email" name="email" type="email" hint="Hint" required />');

        $this->assertStringContainsString('ds-label', $html);
        $this->assertStringContainsString('ds-input', $html);
        $this->assertStringContainsString('ds-hint', $html);
        $this->assertStringContainsString('aria-describedby="email-hint"', $html);

        $error = Blade::render('<x-ui.input label="Email" name="email" error="Wajib diisi." />');

        $this->assertStringContainsString('aria-invalid="true"', $error);
        $this->assertStringContainsString('role="alert"', $error);
        $this->assertStringContainsString('Wajib diisi.', $error);
    }

    public function test_textarea_page_header_and_empty_state_render(): void
    {
        $textarea = Blade::render('<x-ui.textarea label="Teks" name="raw_input">isi</x-ui.textarea>');
        $this->assertStringContainsString('ds-textarea', $textarea);

        $header = Blade::render('<x-ui.page-header title="Judul" description="Desc" />');
        $this->assertStringContainsString('ds-page-header', $header);
        $this->assertStringContainsString('<h1>Judul</h1>', $header);

        $empty = Blade::render('<x-ui.empty-state title="Kosong" description="Desc" />');
        $this->assertStringContainsString('ds-empty', $empty);
    }
}
