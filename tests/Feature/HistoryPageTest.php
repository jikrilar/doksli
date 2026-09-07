<?php

namespace Tests\Feature;

use App\Models\DetectionResult;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * History page contract (UI-v2 Phase 5).
 *
 * Guards the riwayat.blade.php rebuild: filter/search/sort inputs keep their
 * query parameter names, label quick-filters preserve other params, the table
 * keeps detail links + badge statuses + model versions, pagination and the
 * compact summary stay, and empty states guide to the next action — all
 * without legacy card/gradient/emoji patterns.
 */
class HistoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_renders_filters_table_and_summary(): void
    {
        $user = User::factory()->create();
        $valid = $this->completed($user, 'text', 'Berita valid untuk tabel riwayat.', 'valid', 0.9);
        $hoax = $this->completed($user, 'url', 'https://contoh.id/hoax-untuk-tabel', 'hoax', 0.8, 'https://contoh.id/hoax-untuk-tabel');
        $failed = Submission::create([
            'user_id' => $user->id,
            'input_type' => 'image',
            'raw_input' => 'Gambar yang gagal diproses.',
            'status' => 'failed',
        ]);

        $response = $this->actingAs($user)->get(route('riwayat'));

        $response->assertOk();
        $response->assertSee('Riwayat pemeriksaan', false);
        // Filter contracts: names preserved
        foreach (['name="search"', 'name="input_type"', 'name="status"', 'name="sort"'] as $field) {
            $response->assertSee($field, false);
        }
        // Label quick filters keep working links
        $response->assertSee(route('riwayat', ['label' => 'hoax']), false);
        // Table contracts
        $response->assertSee('<table class="ds-table history-table">', false);
        $response->assertSee('ds-badge-valid', false);
        $response->assertSee('ds-badge-hoax', false);
        $response->assertSee('Gagal', false);
        $response->assertSee(route('riwayat.show', $valid), false);
        $response->assertSee(route('riwayat.show', $hoax), false);
        $response->assertSee(route('riwayat.show', $failed), false);
        // Compact summary keeps existing counts (labeled results only)
        $response->assertSee('2 pemeriksaan · 1 valid · 1 hoax · 0 meragukan', false);
        // No legacy patterns
        foreach (['glass-card', 'gradient-text', 'stat-card'] as $banned) {
            $response->assertDontSee($banned);
        }
        $this->assertDoesNotMatchEmoji($response->getContent());
    }

    public function test_history_label_filter_and_reset_link(): void
    {
        $user = User::factory()->create();
        $this->completed($user, 'text', 'Satu berita valid.', 'valid', 0.9);
        $hoax = $this->completed($user, 'text', 'Satu berita hoax.', 'hoax', 0.8);

        $response = $this->actingAs($user)->get(route('riwayat', ['label' => 'hoax']));

        $response->assertOk();
        $response->assertSee(route('riwayat.show', $hoax), false);
        $response->assertSee('aria-current="true"', false);
        // Reset appears when a filter is active
        $response->assertSee('>Reset<', false);
    }

    public function test_history_search_and_sort_keep_query_contract(): void
    {
        $user = User::factory()->create();
        $this->completed($user, 'text', 'Berita tentang jarum suntik vaksinasi massal.', 'valid', 0.9);
        $this->completed($user, 'text', 'Berita tentang panen raya padi.', 'hoax', 0.8);

        $response = $this->actingAs($user)->get(route('riwayat', ['search' => 'vaksinasi', 'sort' => 'oldest']));

        $response->assertOk();
        $response->assertSee('vaksinasi', false);
        $response->assertDontSee('panen raya', false);
        $response->assertSee('value="vaksinasi"', false);
    }

    public function test_history_pagination_preserves_query_string(): void
    {
        $user = User::factory()->create();
        for ($i = 0; $i < 16; $i++) {
            Submission::create([
                'user_id' => $user->id,
                'input_type' => 'text',
                'raw_input' => "Berita paginasi nomor {$i} untuk pengujian riwayat.",
                'status' => 'completed',
            ]);
        }

        $this->actingAs($user)->get(route('riwayat'))
            ->assertOk()
            ->assertSee('page=2', false);

        $this->actingAs($user)->get(route('riwayat', ['page' => 2]))
            ->assertOk();
    }

    public function test_history_empty_states_guide_next_action(): void
    {
        $user = User::factory()->create();

        $fresh = $this->actingAs($user)->get(route('riwayat'));
        $fresh->assertOk();
        $fresh->assertSee('Belum ada riwayat pemeriksaan', false);
        $fresh->assertSee('#cek-berita', false);

        $filtered = $this->actingAs($user)->get(route('riwayat', ['search' => 'tidak-ada-yang-cocok-zzz']));
        $filtered->assertOk();
        $filtered->assertSee('Riwayat tidak ditemukan', false);
        $filtered->assertSee('Reset filter', false);
    }

    public function test_history_displays_persisted_type_labels(): void
    {
        // Note: `video_url` is UI-only and normalized to `video` by
        // InputType::fromRequest before persistence, so only the four
        // persisted values can appear here.
        $user = User::factory()->create();
        Submission::create([
            'user_id' => $user->id,
            'input_type' => 'video',
            'raw_input' => 'Video yang dinormalisasi sebelum disimpan.',
            'status' => 'processing',
        ]);

        $response = $this->actingAs($user)->get(route('riwayat'));

        $response->assertOk();
        $response->assertSee('>Video<', false);
    }

    private function assertDoesNotMatchEmoji(string $html): void
    {
        $main = Str::between($html, '<main id="main-content">', '</main>');
        $this->assertSame(0, preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $main));
    }

    private function completed(User $user, string $type, string $text, string $label, float $confidence, ?string $url = null): Submission
    {
        $submission = Submission::create([
            'user_id' => $user->id,
            'input_type' => $type,
            'raw_input' => $text,
            'source_url' => $url,
            'status' => 'completed',
        ]);
        DetectionResult::create([
            'submission_id' => $submission->id,
            'label' => $label,
            'confidence_score' => $confidence,
            'model_version' => 'v1.0.0-test',
        ]);

        return $submission;
    }
}
