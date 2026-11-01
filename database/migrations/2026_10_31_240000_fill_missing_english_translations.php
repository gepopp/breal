<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit R03/N21: part of the content only exists in German, so the English
 * pages showed German FAQ entries, service tabs and job ads. This fills the
 * missing English texts with machine translations (reviewed by the team).
 *
 * Only empty English values, or ones that are an untouched copy of the German
 * text, are replaced; anything edited in the admin in the meantime stays.
 * Lorem ipsum seed entries are not translated.
 */
return new class extends Migration
{
    /**
     * @var array<string, array<int, array<string, string>>>
     */
    private function translations(): array
    {
        return [
            'questions' => [
                1 => [
                    'question' => 'How do I set up a direct debit for rent payments?',
                    'answer' => '<p>With the SEPA direct debit mandate we can collect the monthly rent and operating costs directly from your bank account. You can download the form <a href="/en/service?tab=sepa-mandat"><span style="text-decoration: underline;">here</span></a>.</p>',
                ],
                2 => [
                    'question' => 'What counts as an emergency?',
                    'answer' => '<p>An emergency is:</p><p>- Burst water pipe</p><p>- Power failure in the entire residential building</p><p>- Heating failure in winter</p><p>- Imminent danger (e.g. falling building parts)</p><p><br>In such cases, please contact our emergency service immediately.<br></p>',
                ],
                3 => [
                    'question' => 'Whom can I contact with further questions?',
                    'answer' => '<p>For further information or specific concerns, you can reach us on +43 1 535 36 19 or by e-mail at <a href="mailto:office@bereal-immobilien.at"><span style="text-decoration: underline;">office@bereal-immobilien.at</span></a>. Our office hours are Monday to Thursday from 8:00 am to 4:00 pm and Friday from 8:00 am to 12:00 pm.</p>',
                ],
                6 => [
                    'question' => 'I would like to make structural changes – what is allowed?',
                    'answer' => '<p>All structural changes (e.g. floor coverings, wall openings, satellite dishes) must be approved by the property management in advance. Please send us an informal request with a sketch or plans.</p>',
                ],
                7 => [
                    'question' => 'When will I receive my operating cost statement?',
                    'answer' => '<p>The annual operating cost statement is usually issued between May and July of the following year. You will receive it in writing by post or, on request, digitally.</p>',
                ],
                9 => [
                    'question' => 'What do operating costs include?',
                    'answer' => '<ul><li>Water, sewage, waste</li><li>Building cleaning, caretaker</li><li>Lift operation</li><li>Insurance</li><li>Administration costs in accordance with the Austrian Tenancy Act (MRG)</li></ul><p><br></p>',
                ],
                12 => [
                    'question' => 'How do I register my new place of residence?',
                    'answer' => '<p>Within three days of moving into your new accommodation, you must register with the responsible municipal office or, in Vienna, the district office (Magistratisches Bezirksamt). For this you need the registration form (Meldezettel) and a valid ID. The form is available online, from the registration authority and at some tobacconists. Please note that a separate form must be completed for each person living in the household. Further information is available at <a href="https://www.help.gv.at"><span style="text-decoration: underline;">help.gv.at</span></a>.</p>',
                ],
                15 => [
                    'question' => 'Who is my contact for damage or repairs?',
                    'answer' => '<p>Please report damage or repair needs in writing to our office or via our online form. In emergencies (burst water pipe, power failure) you can reach our emergency service at the telephone number on your building information board.</p>',
                ],
                19 => [
                    'question' => 'What tips are there for saving energy in the home?',
                    'answer' => '<ul><li><strong>Ventilate properly:</strong> Avoid permanently tilted windows. Instead, ventilate several times a day by opening the windows fully for a short time (shock ventilation).</li><li><strong>Lower the room temperature:</strong> Reducing the room temperature by 1°C can cut heating costs by around 6%.</li></ul><p><br></p>',
                ],
                20 => [
                    'question' => 'How do the house rules work?',
                    'answer' => '<p>The house rules govern how residents live together in the building. You will find them in the entrance area or receive them when you move in. Please note in particular:</p><p>- Quiet hours (10 pm to 6 am)</p><p>- Pets by arrangement</p><p>- No storing of items in the stairwell</p><p><br></p>',
                ],
                21 => [
                    'question' => 'How can I notify you of my new bank details?',
                    'answer' => '<p>Please send us your new bank details in writing or by e-mail with a signature. Alternatively, you can fill out an updated SEPA form.</p>',
                ],
                22 => [
                    'question' => 'How can I terminate my tenancy?',
                    'answer' => '<p>Notice of termination must be given in writing, observing the notice period agreed in the contract (usually 3 months). Please note:</p><p>- Coordinate the handover date</p><p>- Return the apartment broom-clean</p><p>- Handover report on return</p><p><br></p><p><br></p>',
                ],
                23 => [
                    'question' => 'How do I register electricity and gas for my apartment?',
                    'answer' => '<p>The deregistration and re-registration of district heating, gas and electricity is handled by us as property management. Before you hand back your old apartment, please read the meters. For a tariff comparison and further information, you can use the <a href="https://www.e-control.at"><span style="text-decoration: underline;">E-Control tariff calculator</span></a>.</p>',
                ],
                24 => [
                    'question' => 'How do I prevent mould?',
                    'answer' => '<ul><li><strong>Reduce moisture:</strong> Do not dry laundry in the apartment and reduce the number of houseplants.</li><li><strong>Ventilate regularly:</strong> Make sure the air is exchanged regularly to carry moisture away.</li><li><strong>Check maintenance joints:</strong> Regularly inspect silicone or acrylic joints in damp rooms and renew them when necessary to avoid moisture damage.</li></ul><p><br></p>',
                ],
                25 => [
                    'question' => 'What should I do if I lose my keys?',
                    'answer' => '<p>Please report the loss immediately. Depending on the locking system, cylinders may have to be replaced. The cost of this is to be borne by the tenant.</p>',
                ],
                31 => [
                    'question' => 'What is the current state of the discussion on indexation clauses in rental agreements?',
                    'excerpt' => 'Especially since recent ORF reports (in particular Report of 22 July 2025), there has been widespread alarm about a possible obligation for landlords to refund indexation increases to tenants, possibly even retroactively to the start of the tenancy. ',
                    'answer' => '<p>Especially since recent ORF reports (in particular Report of 22 July 2025), there has been widespread alarm about a possible obligation for landlords to refund indexation increases to tenants, possibly even retroactively to the start of the tenancy.&nbsp;</p><p>A sober look at the current situation gives the following picture:&nbsp;</p><p>The discussion began two years ago with judgments in association proceedings (put briefly, these are not about the individual payment claims of a single tenant, but about the general, abstract validity of standard rental agreement provisions or breaches of consumer protection law). Two clauses in particular were found to be unlawful:&nbsp;</p><p>First, the index succession clause (which index applies if the CPI is no longer published), and second, a missing two-month clause, i.e. a provision that expressly excludes an increase for the first two months of the contract.&nbsp;</p><p>Special rules apply to the courts in these association proceedings, in particular the principle of the "interpretation most hostile to the customer" of the contract provision under review and the exclusion of "validity-preserving reduction" (that is: an invalid provision is not to be reduced to the permissible extent).&nbsp;</p><p>The situation is different in individual proceedings, i.e. when an individual tenant claims repayment of paid index increases from the landlord. So far there are only a few relevant individual proceedings, and the decisions available to date give landlords reason for (cautious) optimism in many cases.&nbsp;</p><p>As for the ruling of the Constitutional Court (VfGH) that made waves in July 2025, it should be noted that this ruling has no particular significance for the state of the discussion. It merely clarified that the relevant statutory provisions on consumer protection in Austria are not unconstitutional. A different outcome was realistically not to be expected.&nbsp;</p><p>The federal government already announced in its government programme, and again on the occasion of the Constitutional Court decision in recent weeks, that it will enact a statutory regulation on this topic, which should be awaited first. Such a regulation was announced for the coming autumn.&nbsp;</p><p>The claims now arriving from individual tenants must be examined case by case, in light of the specific statutory framework (full application of the MRG? application of the KSchG?) and the indexation arrangements agreed in the individual case. At least until the statutory regulation is available, any tenant claim raised now will only have to be granted in exceptional cases.&nbsp;</p><p>It remains exciting, and we will keep you up to date.&nbsp;</p><p>In view of the discussion on indexation, we have also revised our rental agreement templates, largely in line with the recommendations of the Austrian Economic Chamber.&nbsp;</p>',
                ],
                32 => [
                    'question' => 'How do I switch my electricity provider in Austria?',
                    'answer' => '<p>Electricity prices in Austria fluctuate widely – and many households are still paying too much. Switching to a cheaper provider is easier than ever, yet many people do not take advantage of it. Why is that? We show you how to switch your electricity provider in just a few minutes – and save in the long term.</p><p><strong>Why switching makes sense</strong></p><p>Prices have stabilised since the energy crisis, but:<br>Many tariffs are still well above the market price. Anyone who does not compare and switch regularly often pays hundreds of euros too much – every year. The new grid costs will also contribute to further price increases.</p><p>Switching provider brings:<br>• Significantly lower electricity costs<br>• Attractive new-customer bonuses<br>• More transparency about your own consumption<br>• If desired: 100% green electricity from Austria<br><br><strong>How switching works – in 3 simple steps</strong><br>1. Go to a comparison portal<br>Use reputable platforms such as the E-Control tariff calculator or durchblicker.at.<br>Enter your postcode, estimated annual consumption and, if applicable, preferences (e.g. green electricity).<br>2. Choose a provider<br>The portal lists the cheapest tariffs including all bonuses. Pay attention to:<br>• Contract term and notice period<br>• Price guarantee (ideally at least 12 months)<br>• Customer reviews<br>3. Switch online – done!<br>The new provider takes care of cancelling with your old provider for you. There is no interruption in the electricity supply – you will not notice the switch apart from the lower bill.<br><br><strong>How long does it take?<br></strong>Switching takes 5 to 10 minutes online. The changeover usually takes place within 2 to 3 weeks. No technician is needed, no meter change – and there are no additional costs.<br><strong>Extra tip: check notice periods<br></strong>Many older contracts include long commitments or automatic renewals. Therefore check your latest electricity bill or your customer portal. With tariffs that have a monthly notice period, you are maximally flexible.</p><p><strong>Conclusion: switching pays off</strong><br>Anyone who switches electricity provider often saves several hundred euros per year – without any effort. Thanks to digital portals, automatic cancellation and legal protection, switching is now as easy as shopping online.<br><br></p>',
                ],
                33 => [
                    'question' => 'What do the new EU rules on short-term rentals mean for me?',
                    'answer' => '<p>Members of the European Parliament have recently presented a legislative proposal for stricter regulation of short-term rentals (STRs) via platforms such as Airbnb and Booking. The aim is a uniform EU directive with minimum standards – and this is also of interest to Austrian owners. We explain how simple the implementation is – and why you should get informed now</p><p><strong>Uniform registration: transparency instead of a grey area<br></strong>The planned directive provides for a simple online registration obligation – including a unique registration number that must in future be visible in every listing. Authorities are to be able to access the data directly from digital platforms – monthly, or quarterly for very small providers.<br>Strengthening authorities: an end to illegal offers<br>Local authorities will in future receive extended powers: they can remove unauthorised listings, suspend registration numbers or oblige hosts to update their details. Platforms must actively remove illegal content and reliably verify information.</p><p><strong>What does this mean specifically for Austria?<br></strong>• The EU regulation must be implemented 24 months after it enters into force – in Austria, therefore, at the earliest from May 2026 (source: Council of the European Union).<br>• A key advantage: uniform standards instead of unclear local rules.<br>• For landlords this means: registration obligation, mandatory display of the number, regular reporting of rental activity to the authorities.<br><br><strong>Why is the effort manageable?</strong><br>• Platform-based registration runs automatically and digitally.<br>• You do not have to deal with any cancellation process – on the contrary: platforms increasingly provide hosting support.<br>• Authorities and portals use standardised data formats, which makes administration easier.<br><br><strong>What should you do now?</strong><br>1. Preparation: Check whether your property is offered or planned as a short-term rental.<br>2. Plan the registration: Have documents ready (address, proof of ownership, etc.) so that registration works as soon as it becomes possible.<br>3. Seek legal advice: especially if you rent commercially or special rules apply in your municipality.<br>4. Establish communication with the authorities: on site or via platforms – also to identify local restrictions early.<br><br></p>',
                ],
            ],
            'services' => [
                6 => [
                    'name' => 'Energy supply',
                    'description' => '<h2><strong>Electricity and gas supply</strong></h2><p>The deregistration and re-registration of district heating/gas and electricity is carried out by us as property management as a service. Before you finally hand back your old apartment, it is therefore advisable to read the meters.</p><p><strong>Tariff calculator</strong> – finding the cheapest electricity and gas offer</p><p><strong>Energy saving check</strong> – determining the annual energy consumption of a household</p><p><strong>Gas conversion check</strong> – converting cubic metres into kilowatt hours to verify the details on your gas bill</p><p>As part of our resident service, this checklist is a guide for your move and does not claim to be complete.</p><p><br></p>',
                ],
                7 => [
                    'name' => 'SEPA mandate',
                    'description' => '<p><strong> Direct debit authorisation for the monthly rent</strong></p><p>For smooth handling of rent payments, we offer the option of a direct debit authorisation. With this authorisation you authorise us to debit the rent monthly from your specified bank account. This not only simplifies payment processing, but also ensures that the rent is paid on time and correctly.</p><p>To grant a direct debit authorisation, we only need your written consent and the relevant bank details. You have the right to revoke or change the direct debit authorisation at any time.</p>',
                ],
                12 => [
                    'name' => 'Registration form (Meldezettel)',
                    'description' => '<p>According to legal regulations, a change of residence must be reported <strong>within 3 days</strong> of moving into a new accommodation to the responsible municipal office or district office (Magistratisches Bezirksamt, Vienna). The registration can be made in person, by post or through a messenger.</p><p><strong>What you need for the registration:</strong></p><ul><li><p>– Registration form (Meldezettel)</p></li><li><p>– ID</p></li></ul><p><strong>Important:</strong></p><ul><li><p>Please note that registrations by fax or e-mail are currently <strong>not legally possible</strong>.</p></li><li><p>Do not forget to deregister/register your dog!</p></li></ul><p></p>',
                ],
            ],
            'contactpeople' => [
                324 => ['position' => 'Reception'],
                337 => ['position' => 'Assistant'],
            ],
            'job_vacancies' => [
                20 => [
                    'title' => 'Property Manager (m/f/d) with Team Leadership',
                    'job_title' => 'Property Manager (m/f/d) with Team Leadership',
                    'description' => '<p><strong>Your future responsibilities:</strong></p><p>• Leading a team of 1–2 employees</p><p>• Managing a selected real estate portfolio in the MRG (Tenancy Act) or WEG (Condominium Act) sector</p><p>• Organising and holding owners meetings</p><p>• Looking after properties from a commercial, technical and organisational perspective</p><p>• Professional, independent support of clients, tenants and owners</p><p>• Planning and implementing maintenance measures</p><p>• Ongoing administrative tasks (e.g. invoice checking, reporting, handling damage cases, etc.)</p><p><strong>Profile:</strong></p><p>• Relevant professional experience in property management is required</p><p>• Training in property management (qualification exam for property managers or an equivalent degree)</p><p>• Good knowledge of the legal fundamentals and an understanding of technical and business contexts</p><p>• Good IT skills (MS Office, REAL Data Systems, DocuWare/JobRouter, etc.)</p><p>• Strong service orientation, solution-oriented thinking, organisational talent, decisiveness</p><p>• Perfect German skills, solid English skills in speaking and writing</p><p>• A high degree of resilience and flexibility</p><p><strong>Benefits</strong>:</p><p>• A long-term secure job</p><p>• A pleasant, appreciative working atmosphere</p><p>• Attractive benefits such as: training and further education, regular company activities, sports offer, job ticket (Wiener Linien annual pass), JobRad</p><p>• Very good public transport connections (U2/U4/1)</p><p>• €63,000.00 gross annual salary on a full-time basis; depending on professional qualification and experience, an overpayment is envisaged</p><p></p><p></p>',
                ],
                21 => [
                    'title' => 'Property Management Clerk (m/f/d)',
                    'job_title' => 'Property Management Clerk (m/f/d)',
                    'description' => '<p><strong>Your future responsibilities:</strong></p><p>• Independent support of clients, tenants and owners</p><p>• Supporting property managers of MRG / WEG properties from a technical, organisational and commercial perspective</p><p>• Ongoing general administrative support tasks (e.g. invoice checking, instructing lawyers, handling damage cases, preparing rental agreements, etc.)</p><p><strong>Profile:</strong></p><p>• Completed training (apprenticeship as a real estate clerk, property management referent) or many years of professional experience in property management</p><p>• Good IT skills (MS Office, REAL Data Systems, DocuWare/JobRouter, etc.)</p><p>• Strong service orientation, solution-oriented thinking, organisational talent, decisiveness</p><p>• Perfect German skills, solid English skills in speaking and writing</p><p>• A high degree of resilience and flexibility</p><p><strong>Benefits:</strong></p><p>• A long-term secure job</p><p>• Internal career opportunities</p><p>• A pleasant, appreciative working atmosphere</p><p>• Attractive benefits such as training and further education, regular company activities, sports offer, job ticket (Wiener Linien annual pass), JobRad</p><p>• Very good public transport connections (U2/U4/1)</p><p>• At least €42,000.00 gross annual salary on a full-time basis; depending on professional qualification and experience, an overpayment is envisaged</p><p></p>',
                ],
                22 => [
                    'title' => 'Property Manager (m/f/d) MRG with Team Leadership',
                    'job_title' => 'Property Manager (m/f/d) MRG with Team Leadership',
                    'description' => '<p><strong>Your future responsibilities:</strong></p><p>• Leading a team of 1–2 employees</p><p>• Managing a selected real estate portfolio in the MRG (Tenancy Act) sector</p><p>• Looking after properties from a commercial, technical and organisational perspective</p><p>• Professional, independent support of clients, tenants and owners</p><p>• Planning and implementing maintenance measures</p><p>• Ongoing administrative tasks (e.g. invoice checking, reporting, handling damage cases, etc.)</p><p><strong>Profile:</strong></p><p>• Relevant professional experience in property management is required</p><p>• Training in property management (qualification exam for property managers or an equivalent degree)</p><p>• Good knowledge of the legal fundamentals and an understanding of technical and business contexts</p><p>• Good IT skills (MS Office, REAL Data Systems, DocuWare/JobRouter, etc.)</p><p>• Strong service orientation, solution-oriented thinking, organisational talent, decisiveness</p><p>• Perfect German skills, solid English skills in speaking and writing</p><p>• A high degree of resilience and flexibility</p><p></p><p><strong>Benefits:</strong></p><p>• A long-term secure job</p><p>• A pleasant, appreciative working atmosphere</p><p>• Attractive benefits such as: training and further education, regular company activities, sports offer, job ticket (Wiener Linien annual pass), JobRad</p><p>• Very good public transport connections (U2/U4/1)</p><p>• €63,000.00 gross annual salary on a full-time basis; depending on professional qualification and experience, an overpayment is envisaged</p>',
                ],
                23 => [
                    'title' => 'Property Manager (m/f/d) WEG with Team Leadership',
                    'job_title' => 'Property Manager (m/f/d) WEG with Team Leadership',
                    'description' => '<p>You know condominium ownership inside out and are ready for the next step?</p><p>To strengthen our team at be real Immobilienmanagement, we are looking for a Property Manager (m/f/d) WEG with Team Leadership</p><p></p><p><strong>Your future responsibilities:</strong></p><p>• Leading a team of 1–2 employees</p><p>• Managing a selected real estate portfolio in the WEG (Condominium Act) sector</p><p>• Organising, holding and following up owners meetings</p><p>• Looking after properties from a commercial, technical and organisational perspective</p><p>• Professional, independent support of clients and owners</p><p>• Planning and implementing maintenance measures</p><p>• Ongoing administrative tasks (e.g. invoice checking, reporting, handling damage cases, etc.)</p><p><strong>Profile:</strong></p><p>• Relevant professional experience in property management is required</p><p>• Training in property management (qualification exam for property managers or an equivalent degree)</p><p>• Good knowledge of the legal fundamentals and an understanding of technical and business contexts</p><p>• Good IT skills (MS Office, REAL Data Systems, DocuWare/JobRouter, etc.)</p><p>• Strong service orientation, solution-oriented thinking, organisational talent, decisiveness</p><p>• Perfect German skills, solid English skills in speaking and writing</p><p>• A high degree of resilience and flexibility</p><p></p><p><strong>Benefits:</strong></p><p>• A long-term secure job</p><p>• A pleasant, appreciative working atmosphere</p><p>• Attractive benefits such as: training and further education, regular company activities, sports offer, job ticket (Wiener Linien annual pass), JobRad</p><p>• Very good public transport connections (U2/U4/1)</p><p>• €63,000.00 gross annual salary on a full-time basis; depending on professional qualification and experience, an overpayment is envisaged</p>',
                ],
            ],
        ];
    }

    public function up(): void
    {
        foreach ($this->translations() as $table => $rows) {
            foreach ($rows as $id => $fields) {
                $record = DB::table($table)->where('id', $id)->first();

                if (! $record) {
                    continue;
                }

                $update = [];

                foreach ($fields as $field => $english) {
                    $values = json_decode((string) $record->{$field}, true);

                    if (! is_array($values) || $this->plain($values['de'] ?? '') === '') {
                        continue;
                    }

                    $current = $this->plain($values['en'] ?? '');

                    if ($current === '' || $this->squash($current) === $this->squash($this->plain($values['de']))) {
                        $values['en'] = $english;
                        $update[$field] = json_encode($values, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                }

                if ($update !== []) {
                    DB::table($table)->where('id', $id)->update($update);
                }
            }
        }
    }

    public function down(): void
    {
        // Added translations are content, nothing to restore.
    }

    /**
     * Stored copies of the German text differ from it in whitespace and spacing between paragraphs only.
     */
    private function squash(string $text): string
    {
        return preg_replace('/\s+/u', '', $text);
    }

    private function plain(string $html): string
    {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5)));
    }
};
