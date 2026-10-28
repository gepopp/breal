<?php

namespace Tests\Feature;

use App\Services\StructuredData;
use Database\Factories\JobVacancyFactory;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StructuredDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The decoded JSON-LD nodes of a page.
     *
     * @return list<array<string, mixed>>
     */
    private function nodesOf(string $path): array
    {
        $html = $this->get($path)->assertOk()->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        return array_map(fn (string $json): array => json_decode($json, true, flags: JSON_THROW_ON_ERROR), $matches[1]);
    }

    /**
     * @param  array<string, mixed>  $node
     * @return array<string, array<string, mixed>>
     */
    private function graphById(array $node): array
    {
        return collect($node['@graph'])->keyBy('@id')->all();
    }

    public function test_section_home_pages_carry_the_organization_graph(): void
    {
        foreach (['/', '/makler/start', '/technik/start'] as $path) {
            $nodes = $this->nodesOf($path);

            $this->assertCount(1, $nodes, $path);
            $this->assertSame('https://schema.org', $nodes[0]['@context']);
            $this->assertCount(4, $nodes[0]['@graph'], $path);
        }
    }

    public function test_group_and_companies_match_the_company_data(): void
    {
        $graph = $this->graphById($this->nodesOf('/')[0]);

        $group = $graph['https://www.bereal-immobilien.at/#group'];
        $this->assertSame('Organization', $group['@type']);
        $this->assertSame('be real Immobilien', $group['name']);
        $this->assertSame('+43 1 535 36 19', $group['telephone']);
        $this->assertSame('Franz-Josefs-Kai 65/Mezzanin', $group['address']['streetAddress']);
        $this->assertSame('1010', $group['address']['postalCode']);
        $this->assertSame('AT', $group['address']['addressCountry']);
        $this->assertCount(3, $group['subOrganization']);
        $this->assertArrayNotHasKey('sameAs', $group, 'no profile URLs are known yet, so none are claimed');

        foreach ($group['subOrganization'] as $reference) {
            $this->assertArrayHasKey($reference['@id'], $graph);
        }

        $makler = $graph['https://www.bereal-makler.at/#organization'];
        $this->assertSame('RealEstateAgent', $makler['@type']);
        $this->assertSame('be real Immobilienmakler GmbH', $makler['legalName']);
        $this->assertSame('office@bereal-makler.at', $makler['email']);
        $this->assertSame('ATU76971617', $makler['vatID']);
        $this->assertSame(['@id' => 'https://www.bereal-immobilien.at/#group'], $makler['parentOrganization']);

        $this->assertSame('be real Baumanagement GmbH', $graph['https://www.bereal-technik.at/#organization']['legalName']);
        $this->assertSame('office@bereal-immobilien.at', $graph['https://www.bereal-immobilien.at/#organization']['email']);
    }

    public function test_opening_hours_are_complete_and_well_formed(): void
    {
        $graph = $this->graphById($this->nodesOf('/')[0]);
        $hours = $graph['https://www.bereal-makler.at/#organization']['openingHoursSpecification'];

        $this->assertSame([['Monday', 'Tuesday', 'Wednesday', 'Thursday'], '08:00', '16:00'], [$hours[0]['dayOfWeek'], $hours[0]['opens'], $hours[0]['closes']]);
        $this->assertSame([['Friday'], '08:00', '12:00'], [$hours[1]['dayOfWeek'], $hours[1]['opens'], $hours[1]['closes']]);
    }

    public function test_pages_without_structured_content_have_no_json_ld(): void
    {
        $this->assertSame([], $this->nodesOf('/team'));
        $this->assertSame([], $this->nodesOf('/impressum'));
    }

    public function test_job_posting_has_the_fields_google_requires(): void
    {
        $job = JobVacancyFactory::new()->create([
            'title' => 'Immobilienverwalter:in (m/w/d)',
            'description' => '<p>Verwalten & <strong>betreuen</strong></p>',
            'company' => 'Makler',
            'contract_type' => 'Vollzeit',
            'unit_text' => 'Jährlich',
            'salary' => 42000,
            'from' => now()->subDay(),
            'to' => now()->addMonth(),
        ]);

        $posting = $this->nodesOf(route('stellenanzeige', $job, false))[0];

        $this->assertSame('JobPosting', $posting['@type']);
        $this->assertSame('Immobilienverwalter:in (m/w/d)', $posting['title']);
        $this->assertSame('<p>Verwalten & <strong>betreuen</strong></p>', $posting['description']);
        $this->assertSame($job->from->toIso8601String(), $posting['datePosted']);
        $this->assertSame($job->to->toIso8601String(), $posting['validThrough']);
        $this->assertSame('be real Immobilienmakler GmbH', $posting['hiringOrganization']['name']);
        $this->assertSame('1010', $posting['jobLocation']['address']['postalCode']);
        $this->assertSame('FULL_TIME', $posting['employmentType']);
        $this->assertSame('EUR', $posting['baseSalary']['currency']);
        $this->assertEquals(42000, $posting['baseSalary']['value']['value']);
        $this->assertSame('YEAR', $posting['baseSalary']['value']['unitText']);
    }

    public function test_job_posting_leaves_out_unknown_employment_type_and_salary(): void
    {
        $job = JobVacancyFactory::new()->create([
            'contract_type' => 'Etwas anderes',
            'unit_text' => 'Pro Projekt',
            'salary' => 0,
            'from' => now()->subDay(),
            'to' => now()->addMonth(),
        ]);

        $posting = StructuredData::jobPosting($job);

        $this->assertArrayNotHasKey('employmentType', $posting);
        $this->assertArrayNotHasKey('baseSalary', $posting);
    }

    public function test_salary_period_typo_in_existing_data_is_recognized(): void
    {
        $job = JobVacancyFactory::new()->create(['unit_text' => 'Wöchnentlich', 'salary' => 500, 'from' => now()->subDay(), 'to' => now()->addMonth()]);

        $this->assertSame('WEEK', StructuredData::jobPosting($job)['baseSalary']['value']['unitText']);
    }

    public function test_faq_page_lists_all_questions_with_answers(): void
    {
        QuestionFactory::new()->create(['question' => 'Wie hoch ist die Verwaltungsgebühr?', 'answer' => '<p>Das hängt vom Objekt ab.</p>']);
        QuestionFactory::new()->create(['question' => 'Wer ist zuständig?', 'answer' => '<p>Ihr Verwalter.</p>']);

        $faq = $this->nodesOf('/faq')[0];

        $this->assertSame('FAQPage', $faq['@type']);
        $this->assertCount(2, $faq['mainEntity']);
        $this->assertContains('Wie hoch ist die Verwaltungsgebühr?', array_column($faq['mainEntity'], 'name'));

        $entity = collect($faq['mainEntity'])->firstWhere('name', 'Wer ist zuständig?');
        $this->assertSame('Question', $entity['@type']);
        $this->assertSame('Answer', $entity['acceptedAnswer']['@type']);
        $this->assertSame('<p>Ihr Verwalter.</p>', $entity['acceptedAnswer']['text']);
    }

    public function test_single_faq_page_describes_its_own_question(): void
    {
        $question = QuestionFactory::new()->create(['question' => 'Einzelfrage?', 'answer' => '<p>Einzelantwort.</p>']);

        $faq = $this->nodesOf(route('faq.single', $question->slug, false))[0];

        $this->assertSame('FAQPage', $faq['@type']);
        $this->assertCount(1, $faq['mainEntity']);
        $this->assertSame('Einzelfrage?', $faq['mainEntity'][0]['name']);
    }

    public function test_faq_page_without_usable_questions_emits_nothing(): void
    {
        $this->assertNull(StructuredData::faqPage([['question' => 'Nur eine Frage', 'answer' => ' <p> </p> '], ['question' => '', 'answer' => 'Antwort']]));
        $this->assertSame([], $this->nodesOf('/faq'));
    }

    public function test_script_tags_cannot_be_broken_out_of(): void
    {
        $html = StructuredData::script([['@context' => 'https://schema.org', '@type' => 'FAQPage', 'name' => '</script><script>alert(1)</script>']]);

        $this->assertSame(1, substr_count($html, '</script>'));
        $this->assertStringNotContainsString('<script>alert', $html);
        $this->assertSame('</script><script>alert(1)</script>', json_decode(substr($html, strlen('<script type="application/ld+json">'), -strlen('</script>')), true)['name']);
    }
}
