<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Homepage + checker contract (UI-v2 Phase 3).
 *
 * Guards everything Phase 3 rebuilt in welcome.blade.php: the four
 * submission forms (actions, field names, honeypot, CAPTCHA, constraints),
 * tab/error/loading element IDs consumed by checker.js, the concise
 * disclaimer, and the absence of legacy AI-slop patterns.
 */
class HomepageTest extends TestCase
{
    public function test_homepage_renders_checker_with_all_contracts(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        // Checker shell + tabs
        $response->assertSee('id="cek-berita"', false);
        $response->assertSee('id="tab-teks"', false);
        $response->assertSee('id="tab-gambar"', false);
        $response->assertSee('id="tab-video"', false);
        $response->assertSee('id="tab-url"', false);
        $response->assertSee('role="tablist"', false);
        // Four forms post to the detection endpoint
        $response->assertSee('id="form-teks"', false);
        $response->assertSee('id="form-gambar"', false);
        $response->assertSee('id="form-video"', false);
        $response->assertSee('id="form-url"', false);
        // input_type contract per form
        $response->assertSee('name="input_type" value="text"', false);
        $response->assertSee('name="input_type" value="image"', false);
        $response->assertSee('id="video-input-type" value="video"', false);
        $response->assertSee('name="input_type" value="url"', false);
        // Field contracts
        $response->assertSee('name="raw_input"', false);
        $response->assertSee('minlength="50"', false);
        $response->assertSee('name="media_file"', false);
        $response->assertSee('name="source_url"', false);
        // Honeypot + CAPTCHA on all four forms
        $this->assertSame(4, substr_count($response->getContent(), 'name="website"'));
        $this->assertSame(4, substr_count($response->getContent(), 'name="captcha_answer"'));
        // Behavior hooks for checker.js / upload.js
        $response->assertSee('id="teks-count"', false);
        $response->assertSee('id="gambar-preview-img"', false);
        $response->assertSee('id="url-preview"', false);
        $response->assertSee('id="loading-overlay"', false);
        $response->assertSee('id="step-1-text"', false);
        // Concise disclaimer kept with real fact-check links
        $response->assertSee('indikatif (estimasi model)', false);
        $response->assertSee('https://cekfakta.com', false);
        // Secondary content without cards/emoji
        $response->assertSee('Klasifikasi IndoBERT', false);
        $response->assertSee('/cara-kerja', false);
        // Legacy AI-slop must be gone from the homepage
        $response->assertDontSee('glass-card');
        $response->assertDontSee('glow-orb');
        $response->assertDontSee('gradient-text');
        $response->assertDontSee('hero-badge');
        $response->assertDontSee('95%+');
        $response->assertDontSee('animate-fade-in-up');
    }

    public function test_checker_validation_errors_are_visible_with_tab_restored(): void
    {
        $this->get('/'); // sets the session CAPTCHA

        $response = $this->post(route('deteksi'), [
            'input_type' => 'image',
            'captcha_answer' => 'definitely-wrong',
        ]);

        $response->assertSessionHasErrors(['media_file', 'captcha_answer']);

        $follow = $this->get('/');

        $follow->assertOk();
        $follow->assertSee('File wajib diunggah.');
        $follow->assertSee('Jawaban CAPTCHA salah.');
        // The image tab stays active after the round-trip
        $follow->assertSee('id="tab-gambar" class="ds-tab" role="tab" data-tab="gambar" aria-selected="true"', false);
        // ...and the video URL mode survives too
        $this->post(route('deteksi'), [
            'input_type' => 'video_url',
            'source_url' => 'not-a-url',
            'captcha_answer' => 'definitely-wrong',
        ])->assertSessionHasErrors(['source_url', 'captcha_answer']);

        $videoFollow = $this->get('/');
        $videoFollow->assertSee('id="video-input-type" value="video_url"', false);
        $videoFollow->assertSee('URL harus valid dan menggunakan protokol HTTP atau HTTPS.');
    }

    public function test_text_input_is_repopulated_after_validation_error(): void
    {
        $this->get('/');

        $this->post(route('deteksi'), [
            'input_type' => 'text',
            'raw_input' => 'terlalu pendek',
            'captcha_answer' => 'definitely-wrong',
        ])->assertSessionHasErrors(['raw_input', 'captcha_answer']);

        $this->get('/')->assertSee('terlalu pendek', false);
    }
}
