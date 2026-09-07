<?php

namespace Tests\Feature;

use App\Models\DetectionResult;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Processing + result page contract (UI-v2 Phase 4).
 *
 * Guards the DS §29 hierarchy on hasil.blade.php and the Livewire progress
 * contract: verdict first with SVG badge (no emoji), confidence secondary
 * without gradients, collapsed technical details, verification guidance,
 * preserved actions/feedback/fallbacks, and no legacy AI-slop classes.
 */
class ResultPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_result_renders_hierarchy_without_banned_patterns(): void
    {
        [$user, $submission] = $this->submissionWithResult('hoax', 0.87);

        $response = $this->actingAs($user)->get(route('hasil', $submission->id));

        $response->assertOk();
        // Level 1–3: badge verdict, interpretation, secondary confidence
        $response->assertSee('Hasil pemeriksaan', false);
        $response->assertSee('ds-badge-hoax', false);
        $this->assertMatchesRegularExpression(
            '/ds-badge-hoax[^>]*>.*?Hoax/s',
            $response->getContent(),
            'Verdict badge shows the Hoax label.'
        );
        $response->assertSee('lebih konsisten dengan konten hoax', false);
        $response->assertSee('Tingkat keyakinan model', false);
        $response->assertSee('role="progressbar"', false);
        $response->assertSee('ds-confidence-fill is-hoax', false);
        // Level 4–7: explanation, content, guidance, collapsed tech details
        $response->assertSee('Penjelasan hasil', false);
        $response->assertSee('Contoh penjelasan naratif untuk pengujian.', false);
        $response->assertSee('Konten yang dianalisis', false);
        $response->assertSee('Verifikasi hasil ini', false);
        $response->assertSee('estimasi model', false);
        $response->assertSee('https://cekfakta.com', false);
        $response->assertSee('<details class="ds-tech mt-6">', false);
        $response->assertSee('v1.0.0-test', false);
        // Actions preserved
        $response->assertSee(route('hasil.pdf', $submission->id));
        $response->assertSee('Cek berita lain', false);
        // Feedback form for the owner with behavior hooks
        $response->assertSee('id="feedback-form"', false);
        $response->assertSee('id="feedback-is-correct"', false);
        $response->assertSee('name="comment"', false);
        // Banned patterns must be gone
        foreach (['glass-card', 'gradient-text', 'glow-orb', 'Penjelasan AI'] as $banned) {
            $response->assertDontSee($banned, false);
        }
        $this->assertDoesNotMatchEmoji($response->getContent());
    }

    public function test_processing_state_keeps_livewire_polling_and_fallbacks(): void
    {
        $submission = Submission::create([
            'user_id' => null,
            'input_type' => 'text',
            'raw_input' => 'Submission yang masih diproses oleh antrean.',
            'status' => 'processing',
            'processing_stage' => 'classifying',
        ]);

        $response = $this->get(route('hasil', $submission->id));

        $response->assertOk();
        $response->assertSee('wire:poll.2s.visible="refreshProgress"', false);
        $response->assertSee('role="progressbar"', false);
        $response->assertSee('data-stage-label', false);
        $response->assertSee('data-status-url="'.route('hasil.status', $submission->id).'"', false);
        $response->assertSee('<noscript>', false);
        $response->assertSee('id="print-btn"', false);
    }

    public function test_failed_state_shows_failure_reason(): void
    {
        $submission = Submission::create([
            'user_id' => null,
            'input_type' => 'text',
            'raw_input' => 'Submission yang gagal diproses.',
            'status' => 'failed',
            'processing_stage' => 'classifying',
            'failure_reason' => 'Layanan inferensi BERT tidak dapat dihubungi.',
        ]);

        $this->get(route('hasil', $submission->id))
            ->assertOk()
            ->assertSee('Pemrosesan Gagal', false)
            ->assertSee('Layanan inferensi BERT tidak dapat dihubungi.', false);
    }

    private function assertDoesNotMatchEmoji(string $html): void
    {
        // Scoped to page content: the shared layout favicon is a documented
        // Phase 8 asset decision, not a UI icon.
        $main = Str::between($html, '<main id="main-content">', '</main>');
        $this->assertSame(
            0,
            preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $main),
            'Result page must not use emoji as UI icons.'
        );
    }

    /**
     * @return array{User, Submission}
     */
    private function submissionWithResult(string $label = 'hoax', float $confidence = 0.87): array
    {
        $user = User::factory()->create();
        $submission = Submission::create([
            'user_id' => $user->id,
            'input_type' => 'text',
            'raw_input' => 'Teks berita yang sudah selesai dianalisis model.',
            'status' => 'completed',
        ]);
        DetectionResult::create([
            'submission_id' => $submission->id,
            'label' => $label,
            'confidence_score' => $confidence,
            'model_version' => 'v1.0.0-test',
            'explanation' => 'Contoh penjelasan naratif untuk pengujian.',
        ]);

        return [$user, $submission];
    }
}
