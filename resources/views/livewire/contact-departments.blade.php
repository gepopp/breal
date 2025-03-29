<section>
    <h2 @class([
        "font-bold text-3xl mb-8",
        "text-logo-950" => !Route::is('technik.*', 'immobilien.*'),
        "text-technik-950" => Route::is('technik.*'),
        "text-makler-950" => Route::is('immobilien.*'),
    ])>Ihre Ansprechpartner</h2>
    <p>Zusammenarbeit funktioniert am besten, wenn sie wissen, wer am anderen Ende der Leitung sitzt. Hier dürfen wir Ihnen die zuständigen MitarbeiterInnen vorstellen.</p>
</section>
