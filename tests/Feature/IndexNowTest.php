<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Jobs\SubmitToIndexNow;
use App\Models\Realty;
use App\Services\IndexNow;
use App\Services\IndexNowNotifier;
use App\Settings\FaqSettings;
use Database\Factories\CompetenceFactory;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Spatie\LaravelSettings\Events\SettingsSaved;
use Tests\TestCase;

class IndexNowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['app.url' => 'https://www.bereal-immobilien.at']);
        URL::forceRootUrl('https://www.bereal-immobilien.at');
        URL::forceScheme('https');
    }

    private function enable(): void
    {
        config(['indexnow.enabled' => true]);
        Queue::fake();
    }

    /**
     * Runs the job the way the queue worker does and returns the addresses it sent (null when nothing was sent).
     *
     * @return list<string>|null
     */
    private function runJob(): ?array
    {
        Http::fake(['api.indexnow.org/*' => Http::response('', 200)]);

        (new SubmitToIndexNow)->handle(app(IndexNowNotifier::class), app(IndexNow::class));

        $sent = null;

        Http::assertSent(function (Request $request) use (&$sent): bool {
            $sent = $request['urlList'];

            return true;
        });

        return $sent;
    }

    public function test_the_key_file_is_served_with_the_key_as_content(): void
    {
        $key = config('indexnow.key');

        $this->assertFileExists(public_path($key.'.txt'));
        $this->assertSame($key, trim(file_get_contents(public_path($key.'.txt'))));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $key);
    }

    public function test_nothing_is_reported_while_indexnow_is_switched_off(): void
    {
        Queue::fake();

        CompetenceFactory::new()->create();

        Queue::assertNothingPushed();
    }

    public function test_saving_a_competence_reports_its_pages_in_both_languages(): void
    {
        $this->enable();

        $competence = CompetenceFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung]);

        Queue::assertPushed(SubmitToIndexNow::class, 1);

        $sent = $this->runJob();

        $this->assertContains(route('hausverwaltung.leistung', $competence), $sent);
        $this->assertContains('https://www.bereal-immobilien.at/en/leistung/'.$competence->slug, $sent);
        $this->assertContains(route('hausverwaltung.home'), $sent);
        $this->assertContains(route('hausverwaltung.leistungen'), $sent);
        $this->assertContains('https://www.bereal-immobilien.at/en/leistungen', $sent);
    }

    public function test_the_request_carries_host_key_and_key_location(): void
    {
        $this->enable();

        CompetenceFactory::new()->create();
        $this->runJob();

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://api.indexnow.org/indexnow'
            && $request['host'] === 'www.bereal-immobilien.at'
            && $request['key'] === config('indexnow.key')
            && $request['keyLocation'] === 'https://www.bereal-immobilien.at/'.config('indexnow.key').'.txt');
    }

    public function test_many_changes_are_sent_together_in_one_job_and_one_request(): void
    {
        $this->enable();

        CompetenceFactory::new()->count(3)->create();
        QuestionFactory::new()->create();

        Queue::assertPushed(SubmitToIndexNow::class, 1);

        $this->runJob();

        Http::assertSentCount(1);
    }

    public function test_a_save_without_a_change_reports_nothing(): void
    {
        $competence = CompetenceFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung]);

        $this->enable();

        $competence->fresh()->save();

        Queue::assertNothingPushed();
    }

    public function test_deleting_a_question_reports_its_page_so_the_engine_sees_it_gone(): void
    {
        $question = QuestionFactory::new()->create();

        $this->enable();

        $question->delete();

        $sent = $this->runJob();

        $this->assertContains(route('faq.single', $question->slug), $sent);
        $this->assertContains(route('hausverwaltung.faq'), $sent);
    }

    public function test_a_new_property_reports_its_page_and_the_property_search(): void
    {
        $this->enable();

        $realty = Realty::create(['title' => 'Wohnung IndexNow', 'slug' => 'wohnung-indexnow', 'data' => []]);

        $sent = $this->runJob();

        $this->assertContains(route('makler.immobilie', $realty), $sent);
        $this->assertContains(route('makler.immobiliensuche'), $sent);
    }

    public function test_saving_settings_reports_the_pages_that_show_them(): void
    {
        $this->enable();

        $settings = FaqSettings::fake([
            'faq_header_de' => 'a', 'faq_header_en' => 'a', 'faq_subheader_de' => 'b', 'faq_subheader_en' => 'b', 'faq_introtext_de' => 'c', 'faq_introtext_en' => 'c',
        ]);

        event(new SettingsSaved($settings));

        $sent = $this->runJob();

        $this->assertContains(route('hausverwaltung.faq'), $sent);
        $this->assertContains(route('makler.faq'), $sent);
        $this->assertContains('https://www.bereal-immobilien.at/en/technik/faq', $sent);
        $this->assertNotContains(route('hausverwaltung.kontakt'), $sent);
    }

    public function test_addresses_of_other_hosts_are_not_sent(): void
    {
        Http::fake();

        $sent = app(IndexNow::class)->submit(['https://www.bereal-immobilien.at/team', 'https://staging.example.com/team', 'https://www.bereal-immobilien.at/team']);

        $this->assertSame(1, $sent);
        Http::assertSent(fn (Request $request): bool => $request['urlList'] === ['https://www.bereal-immobilien.at/team']);
    }

    public function test_a_failed_request_keeps_the_addresses_for_the_retry(): void
    {
        $this->enable();

        CompetenceFactory::new()->create();

        Http::fake(['api.indexnow.org/*' => Http::response('', 500)]);

        try {
            (new SubmitToIndexNow)->handle(app(IndexNowNotifier::class), app(IndexNow::class));
            $this->fail('The job should fail so that the queue retries it.');
        } catch (RequestException) {
            // expected
        }

        $this->assertContains(route('hausverwaltung.leistungen'), app(IndexNowNotifier::class)->takePending());
    }

    public function test_the_command_lists_every_sitemap_address_without_sending(): void
    {
        Http::fake();

        $this->artisan('indexnow:submit --dry-run')
            ->expectsOutputToContain(route('hausverwaltung.home'))
            ->assertSuccessful();

        Http::assertNothingSent();
    }

    public function test_the_command_refuses_to_send_while_indexnow_is_switched_off(): void
    {
        Http::fake();

        $this->artisan('indexnow:submit')->assertFailed();

        Http::assertNothingSent();
    }

    public function test_the_command_sends_the_sitemap_addresses_when_enabled(): void
    {
        config(['indexnow.enabled' => true]);
        Http::fake(['api.indexnow.org/*' => Http::response('', 200)]);

        $this->artisan('indexnow:submit')->assertSuccessful();

        Http::assertSent(fn (Request $request): bool => in_array(route('hausverwaltung.home'), $request['urlList'], true));
    }
}
