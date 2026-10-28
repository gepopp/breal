<?php

namespace App\Services;

use App\Enums\CompaniesEnum;
use App\Models\JobVacancy;

/**
 * schema.org JSON-LD for the public pages. Company facts come from
 * config/company.php (via CompanyInfo), so they match the visible pages.
 */
class StructuredData
{
    private const CONTEXT = 'https://schema.org';

    private const DAYS = [
        'mon_thu' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday'],
        'fri' => ['Friday'],
    ];

    private const EMPLOYMENT_TYPES = [
        'vollzeit' => 'FULL_TIME',
        'teilzeit' => 'PART_TIME',
        'geringfügig' => 'PART_TIME',
        'freiberuflich' => 'CONTRACTOR',
    ];

    /**
     * Prefixes of the pay period texts ("Wöchnentlich" is a typo in existing data).
     */
    private const SALARY_UNITS = [
        'stündlich' => 'HOUR',
        'wöch' => 'WEEK',
        'monatlich' => 'MONTH',
        'jährlich' => 'YEAR',
    ];

    /**
     * The group and its three companies as one graph.
     *
     * @return array<string, mixed>
     */
    public static function organizations(): array
    {
        $companies = array_map(
            fn (CompaniesEnum $company): array => self::company($company->name),
            CompaniesEnum::cases()
        );

        return [
            '@context' => self::CONTEXT,
            '@graph' => [self::group($companies), ...$companies],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function jobPosting(JobVacancy $jobVacancy): array
    {
        $company = $jobVacancy->company;
        $employer = CompanyInfo::section($company instanceof CompaniesEnum ? $company->name : $company);

        $posting = [
            '@context' => self::CONTEXT,
            '@type' => 'JobPosting',
            'title' => self::text($jobVacancy->title),
            'description' => trim((string) $jobVacancy->description),
            'datePosted' => $jobVacancy->from->toIso8601String(),
            'validThrough' => $jobVacancy->to->toIso8601String(),
            'directApply' => false,
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $employer['legal_name'],
                'sameAs' => $employer['website'],
                'logo' => asset($employer['logo']),
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => self::address(),
            ],
        ];

        if ($employmentType = self::EMPLOYMENT_TYPES[mb_strtolower(trim((string) $jobVacancy->contract_type))] ?? null) {
            $posting['employmentType'] = $employmentType;
        }

        if ($salary = self::baseSalary($jobVacancy)) {
            $posting['baseSalary'] = $salary;
        }

        return $posting;
    }

    /**
     * @param  list<array{question: ?string, answer: ?string}>  $items
     * @return array<string, mixed>|null null when no item has both a question and an answer
     */
    public static function faqPage(array $items): ?array
    {
        $entities = [];

        foreach ($items as $item) {
            $question = self::text($item['question'] ?? '');
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' || trim(strip_tags($answer)) === '') {
                continue;
            }

            $entities[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer],
            ];
        }

        return $entities === [] ? null : [
            '@context' => self::CONTEXT,
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    /**
     * The <script type="application/ld+json"> tags for the given nodes.
     *
     * @param  list<array<string, mixed>|null>  $nodes
     */
    public static function script(array $nodes): string
    {
        return collect($nodes)
            ->filter()
            ->map(fn (array $node): string => '<script type="application/ld+json">'
                .json_encode($node, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_THROW_ON_ERROR)
                .'</script>')
            ->implode("\n");
    }

    /**
     * @param  list<array<string, mixed>>  $companies
     * @return array<string, mixed>
     */
    private static function group(array $companies): array
    {
        $group = config('company.group');

        return array_filter([
            '@type' => 'Organization',
            '@id' => self::groupId(),
            'name' => $group['name'],
            'url' => $group['website'],
            'logo' => asset($group['logo']),
            'email' => CompanyInfo::section(CompaniesEnum::Hausverwaltung->name)['email'],
            'telephone' => CompanyInfo::phone(),
            'faxNumber' => CompanyInfo::fax(),
            'address' => self::address(),
            'sameAs' => $group['social_profiles'],
            'subOrganization' => array_map(fn (array $company): array => ['@id' => $company['@id']], $companies),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private static function company(string $name): array
    {
        $section = CompanyInfo::section($name);

        return array_filter([
            '@type' => $name === CompaniesEnum::Makler->name ? 'RealEstateAgent' : 'LocalBusiness',
            '@id' => $section['website'].'/#organization',
            'name' => $section['name'],
            'legalName' => $section['legal_name'],
            'url' => $section['website'],
            'logo' => asset($section['logo']),
            'email' => $section['email'],
            'telephone' => CompanyInfo::phone(),
            'faxNumber' => CompanyInfo::fax(),
            'vatID' => $section['uid'],
            'address' => self::address(),
            'openingHoursSpecification' => self::openingHours(),
            'parentOrganization' => ['@id' => self::groupId()],
        ]);
    }

    private static function groupId(): string
    {
        return config('company.group.website').'/#group';
    }

    /**
     * @return array<string, string>
     */
    private static function address(): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => config('company.address.street'),
            'postalCode' => config('company.address.postal_code'),
            'addressLocality' => config('company.address.city'),
            'addressCountry' => config('company.address.country'),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function openingHours(): array
    {
        $specifications = [];

        foreach (config('company.opening_hours') as $group => $ranges) {
            foreach ($ranges as $range) {
                [$opens, $closes] = explode('-', $range);

                $specifications[] = [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => self::DAYS[$group],
                    'opens' => $opens,
                    'closes' => $closes,
                ];
            }
        }

        return $specifications;
    }

    /**
     * @return array<string, mixed>|null null without a salary or with a pay period that is not recognized
     */
    private static function baseSalary(JobVacancy $jobVacancy): ?array
    {
        $unit = mb_strtolower(trim((string) $jobVacancy->unit_text));

        foreach (self::SALARY_UNITS as $prefix => $unitText) {
            if ($jobVacancy->salary > 0 && str_starts_with($unit, $prefix)) {
                return [
                    '@type' => 'MonetaryAmount',
                    'currency' => 'EUR',
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'value' => (float) $jobVacancy->salary,
                        'unitText' => $unitText,
                    ],
                ];
            }
        }

        return null;
    }

    private static function text(?string $html): string
    {
        return PageMeta::normalizeTitle((string) $html);
    }
}
