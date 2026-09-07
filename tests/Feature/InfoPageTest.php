<?php

namespace Tests\Feature;

use App\Models\DetectionResult;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Informational pages contract (UI-v2 Phase 7).
 *
 * Guards the cara-kerja/tentang/kebijakan-privasi/statistik rebuilds:
 * methodology and legal content preserved verbatim where required,
 * documentation/timeline and prose patterns used, charts keep their data
 * contracts — all without legacy glow/glass/gradient/emoji patterns.
 */
class InfoPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_cara_kerja_preserves_methodology_without_banned_patterns(): void
    {
        $response = $this->get(route('cara-kerja'));

        $response->assertOk();
        // Methodology facts preserved
        foreach (['IndoBERT', 'OCR', 'Whisper', 'WordPiece', 'Bidireksional', 'Fine-tuned', 'Valid', 'OpenAI'] as $fact) {
            $response->assertSee($fact, false);
        }
        // Timeline pattern + technical disclosure + CTA
        $response->assertSee('<ol class="steps">', false);
        $response->assertSee('<details class="ds-tech', false);
        $response->assertSee('#cek-berita', false);
        $this->assertNoLegacyInfoPatterns($response->getContent());
    }

    public function test_tentang_preserves_content_without_accuracy_claims(): void
    {
        $response = $this->get(route('tentang'));

        $response->assertOk();
        $response->assertSee('Misi kami', false);
        // Developer attribution preserved verbatim (see report: conflicts
        // with the footer author name and needs an owner decision).
        $response->assertSee('Abdan Dzul Ghaffar Razaq', false);
        $response->assertSee('Rancang Bangun Sistem Pendeteksi Berita Hoax', false);
        foreach (['Laravel', 'IndoBERT', 'Hugging Face', 'MySQL'] as $tech) {
            $response->assertSee($tech, false);
        }
        $response->assertSee('indikasi probabilistik', false);
        // Unsupported accuracy marketing must be gone
        $response->assertDontSee('95%+');
        $response->assertDontSee('Target Akurasi Model');
        $this->assertNoLegacyInfoPatterns($response->getContent());
    }

    public function test_kebijakan_privasi_preserves_legal_content_verbatim(): void
    {
        $response = $this->get(route('kebijakan-privasi'));

        $response->assertOk();
        foreach (['1. Pendahuluan', '2. Data yang Dikumpulkan', '3. Penggunaan Data', '4. Layanan Pihak Ketiga', '5. Retensi Data', '6. Hak Pengguna'] as $heading) {
            $response->assertSee($heading, false);
        }
        $response->assertSee('paling lambat 24 jam setelah unggahan', false);
        $response->assertSee('https://openai.com/policies/privacy-policy', false);
        $response->assertSee('Kebijakan ini dapat berubah sewaktu-waktu', false);
        $this->assertNoLegacyInfoPatterns($response->getContent());
    }

    public function test_statistik_renders_chart_and_distribution_contracts(): void
    {
        $hoax = Submission::create([
            'user_id' => null,
            'input_type' => 'text',
            'raw_input' => 'Submission hoax untuk grafik statistik.',
            'status' => 'completed',
            'processing_stage' => 'done',
        ]);
        DetectionResult::create([
            'submission_id' => $hoax->id,
            'label' => 'hoax',
            'confidence_score' => 0.9,
            'model_version' => 'v1.0.0',
        ]);
        $valid = Submission::create([
            'user_id' => null,
            'input_type' => 'text',
            'raw_input' => 'Submission valid untuk grafik statistik.',
            'status' => 'completed',
            'processing_stage' => 'done',
        ]);
        DetectionResult::create([
            'submission_id' => $valid->id,
            'label' => 'valid',
            'confidence_score' => 0.9,
            'model_version' => 'v1.0.0',
        ]);

        $response = $this->get(route('statistik'));

        $response->assertOk();
        $response->assertSee('id="trendChart"', false);
        $response->assertSee('cdn.jsdelivr.net/npm/chart.js', false);
        $response->assertSee('ds-badge-hoax', false);
        $response->assertSee('ds-badge-valid', false);
        // Plain-text summary with real counts (2 labeled: 1 valid, 1 hoax)
        $response->assertSee('2 hasil berlabel · 1 valid · 1 hoax · 0 meragukan', false);
        $this->assertNoLegacyInfoPatterns($response->getContent());
    }

    private function assertNoLegacyInfoPatterns(string $html): void
    {
        $main = Str::between($html, '<main id="main-content">', '</main>');
        foreach (['glass-card', 'glow-orb', 'gradient-text', 'hero-badge'] as $banned) {
            $this->assertStringNotContainsString($banned, $main);
        }
        $this->assertSame(0, preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $main));
    }
}
