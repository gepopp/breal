<?php

namespace App\Settings;

class HausverwaltungLandingpageSettings extends BaseSettings
{
    public array|int|null $hero_image = null;
    public int $hero_speed = 4000;

    public string $hero_image_alt_de = 'be real Team';
    public string $hero_image_alt_en = 'be real Team';

    public string $hero_header_de = 'welcome';
    public string $hero_header_en = 'welcome';

    public string $hero_subheader_de = 'Willkommen bei be real Immobilien. Hausverwaltung ist für uns mehr als nur ein Beruf – wir leben Immobilienverwaltung mit höchstem Anspruch und persönlicher Note.';
    public string $hero_subheader_en = 'Welcome to be real Immobilien. Property management is more than just a profession for us – we live property management with the highest standards and a personal touch.';

    public string $hero_introtext_de = '<p>Wir kümmern uns um Ihre Immobilie, als wäre sie unsere eigene – kompetent, lösungsorientiert und mit Weitblick. Ob Zinshaus, Wohnungseigentum oder ein vielseitiges Portfolio – wir bieten klare Strukturen, effiziente Abläufe und persönlichen Service.</p>';
    public string $hero_introtext_en = '<p>We take care of your property as if it were our own – competent, solution-oriented and with foresight. Whether apartment building, condominium or a diverse portfolio – we offer clear structures, efficient processes and personal service.</p>';

    public string $intro_layout = 'two_columns';

    public string $hero_text_column_one_de = '<p>Unser Ziel ist es, den Wert jeder Immobilie zu bewahren, aber auch zu steigern, indem wir sie mit Sorgfalt und Hingabe pflegen. Wir streben jeden Tag danach, die Erwartungen unserer Kunden zu übertreffen. Wenn unsere Arbeit so nahtlos verläuft, dass niemand merkt, dass wir im Hintergrund agieren, dann wissen wir, dass wir unsere Aufgabe erfolgreich erfüllt haben. Wir setzen uns mit Begeisterung dafür ein, dass die uns anvertraute Immobilie nicht nur rundum betreut, sondern auch verwaltet und schlussendlich zeitgemäß gemanaged wird.</p>';
    public string $hero_text_column_one_en = '<p>Our goal is to preserve, but also increase the value of every property by caring for it with diligence and dedication. We strive every day to exceed our customers\' expectations. When our work runs so seamlessly that no one notices we are acting in the background, we know we have successfully fulfilled our task. We are passionately committed to ensuring that the property entrusted to us is not only comprehensively cared for, but also managed and ultimately managed in a contemporary manner.</p>';

    public string $hero_text_column_two_de = '<p>Sollte aber doch mal wo der Haken drin sein, dann sind wir zur Stelle und bereit jedes Problem zu lösen: von der störrischen Glühbirne über jegliche Schadensfälle bis hin zu komplexen Sanierungen - wir nehmen uns Ihrer Problemen an!</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>';
    public string $hero_text_column_two_en = '<p>But if there is a problem somewhere, we are there and ready to solve every problem: from the stubborn light bulb to any damage cases to complex renovations - we take care of your problems!</p><p>It fills us with joy to master challenges and find tailor-made solutions for you!</p>';

    public string $text_de = '<p>Bei be real verstehen wir Immobilienmanagement als eine langfristige Verantwortung. Unser Ziel ist es, Werte zu bewahren und nachhaltig weiterzuentwickeln. Jede Immobilie betreuen wir mit größter Sorgfalt, Weitblick und persönlichem Engagement – als wäre sie unser eigenes Eigentum. Durch vorausschauendes Handeln und Liebe zum Detail sorgen wir dafür, dass Immobilien nicht nur ihren Wert erhalten, sondern auch an Qualität und Substanz gewinnen.</p><p>Unser Anspruch ist höchste Qualität in jeder Phase der Verwaltung – sichtbar im Ergebnis und spürbar im Alltag. Wir arbeiten effizient, lösungsorientiert und mit Feingefühl, damit Eigentümer:innen und Bewohner:innen entlastet werden und sich auf das Wesentliche konzentrieren können. Denn exzellentes Immobilienmanagement zeigt sich nicht in Lautstärke, sondern in Wirkung und Verlässlichkeit.</p><p>Immobilien sind lebendige Werte, die Geschichten erzählen. Wir begleiten sie mit Umsicht, Fachwissen und Leidenschaft in die Zukunft – und schaffen den Rahmen dafür, dass sich Werte entfalten und Generationen überdauern. Unser Ziel ist es, Erwartungen nicht nur zu erfüllen, sondern täglich zu übertreffen.</p><p><strong>Lernen Sie uns persönlich kennen – wir freuen uns auf Ihre Anfrage und darauf, mit Ihnen gemeinsam Zukunft zu gestalten.</strong></p>';
    public string $text_en = '<p>At be real we understand property management as a long-term responsibility. Our goal is to preserve values and develop them sustainably. We care for each property with the utmost care, foresight and personal commitment – as if it were our own property. Through proactive action and attention to detail, we ensure that properties not only retain their value, but also gain quality and substance.</p><p>Our claim is the highest quality in every phase of management – visible in the result and noticeable in everyday life. We work efficiently, solution-oriented and with sensitivity, so that owners and residents are relieved and can concentrate on what is essential. Because excellent property management is not shown in volume, but in effect and reliability.</p><p>Properties are living values that tell stories. We accompany them into the future with foresight, expertise and passion – and create the framework for values to unfold and endure for generations. Our goal is not only to meet expectations, but to exceed them daily.</p><p><strong>Get to know us personally – we look forward to your inquiry and to shaping the future together with you.</strong></p>';

    public array|int|null $text_image = 0;

    public string $text_image_alt_de = 'be real Lukas Eybel';
    public string $text_image_alt_en = 'be real Lukas Eybel';

    public string $about_header_de = 'with us';
    public string $about_header_en = 'with us';

    public string $about_subheader_de = 'Warum be real?';
    public string $about_subheader_en = 'Why be real?';

    public string $about_text_de = '<p>Als Familienbetrieb wissen wir aus eigener Erfahrung, dass dort, wo viele Menschen unter einem Dach leben oder arbeiten, ganz unterschiedliche Meinungen, Bedürfnisse und Erwartungen aufeinandertreffen. Genau deshalb ist jede Immobilie für uns so einzigartig wie die Menschen, die mit ihr verbunden sind.</p><p>Wir verbinden traditionelle Werte mit moderner Immobilienverwaltung – getragen von echter Serviceorientierung. Unser Ziel ist es, nicht nur Lösungen zu liefern, sondern Menschen wirklich zu verstehen. Mit einem feinen Gespür für individuelle Anforderungen, nachhaltigen Konzepten und einem offenen Ohr für alle Beteiligten gestalten wir Hausverwaltung persönlich, verantwortungsvoll und auf Augenhöhe.</p><p>Hausverwaltung ist für uns weit mehr als ein Beruf – sie ist unsere Leidenschaft. Mit Engagement, Sorgfalt und Begeisterung begleiten wir jedes Projekt – professionell, herzlich und immer im Dienst unserer Kundinnen und Kunden.</p>';
    public string $about_text_en = '<p>As a family business, we know from our own experience that where many people live or work under one roof, very different opinions, needs and expectations collide. That is exactly why every property is as unique to us as the people connected to it.</p><p>We combine traditional values with modern property management – supported by genuine service orientation. Our goal is not only to deliver solutions, but to truly understand people. With a fine sense for individual requirements, sustainable concepts and an open ear for all parties involved, we shape property management personally, responsibly and on equal terms.</p><p>Property management is far more than a profession for us – it is our passion. With commitment, care and enthusiasm, we accompany every project – professionally, warmly and always in service to our customers.</p>';

    public array|int|null $about_image = 0;

    public string $about_image_alt_de = 'animation';
    public string $about_image_alt_en = 'animation';

    public ?string $about_video_embed_code = '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/1079679108?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="bereal_animation_2025"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';

    public string $timeline_header_de = 'strong';
    public string $timeline_header_en = 'strong';

    public string $timeline_subheader_de = 'Unsere Geschichte';
    public string $timeline_subheader_en = 'Our Story';

    public string $timeline_intro_de = '<p>Vom inhabergeführten Einzelunternehmen zum generationsübergreifenden Familienunternehmen und heute zur dynamischen Kraft am Immobilienmarkt, das ist die Geschichte von be real. Seit mehreren Jahrzehnten wurde die Hausverwaltung von verschiedenen Familien geführt – jede von ihnen hat das Unternehmen in unterschiedlichen Rechtsformen weiterentwickelt und mit ihrem persönlichen Engagement geprägt. Was alle eint, ist die Begeisterung für Immobilienmanagement, die Fähigkeit, individuelle Lösungen zu finden, und der Anspruch, Eigentümer:innen und Mieter:innen persönlich und verlässlich zu betreuen.</p><p>Mit dem Zusammenschluss der Bontus Immobilienverwaltung und der Dr. Gerhard Stingl Hausverwaltung im Jahr 2023 wird diese Erfolgsgeschichte nun von Tanja Bachschwöller und Lukas Eybel unter der gemeinsamen Marke <em>be real</em> fortgeschrieben – getragen von Erfahrung, Wertebewusstsein und dem klaren Fokus auf Qualität.</p>';
    public string $timeline_intro_en = '<p>From an owner-managed sole proprietorship to a cross-generational family business and today to a dynamic force in the real estate market, this is the story of be real. For several decades, property management has been run by various families – each of them has further developed the company in different legal forms and shaped it with their personal commitment. What unites them all is the enthusiasm for property management, the ability to find individual solutions, and the claim to personally and reliably support owners and tenants.</p><p>With the merger of Bontus Immobilienverwaltung and Dr. Gerhard Stingl Hausverwaltung in 2023, this success story is now being continued by Tanja Bachschwöller and Lukas Eybel under the common brand <em>be real</em> – supported by experience, value consciousness and a clear focus on quality.</p>';

    public int $timeline_speed = 4000;

    public string $service_heading_de = 'trusted';
    public string $service_heading_en = 'trusted';

    public string $service_subheading_de = 'Unser Versprechen';
    public string $service_subheading_en = 'Our Promise';

    public string $service_introtext_de = '<p>Die Qualität unserer Arbeit, der konkrete Nutzen und das echte Interesse an den Anliegen unserer Kundinnen und Kunden – ob private Hauseigentümer:innen, Wohnungseigentümergemeinschaften, Unternehmer, Family Offices oder institutionelle Investoren – stehen im Mittelpunkt unseres Handelns. Wir stehen nicht nur für professionelle Immobilienverwaltung, sondern auch für persönliche Betreuung, fundierte Beratung und die nachhaltige Wertsteigerung der uns anvertrauten Immobilien.</p><p>Wir denken über den Tellerrand hinaus, übernehmen Verantwortung und gehen die Extrameile – damit der Wert Ihrer Immobilie nicht nur erhalten bleibt, sondern langfristig wächst.</p><p>Unsere größte Freude ist es, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden. Denn am Ende zählt für uns nur eines, Ihre Zufriedenheit.</p><p>Mit <em>be real</em> haben wir eine Marke geschaffen, die Bewährtes mit Innovation vereint. Für uns geht es nicht nur darum, Immobilien zu verwalten – wir wollen sie gemeinsam mit Ihnen aktiv gestalten und weiterentwickeln.</p><p>Herzlich willkommen bei be real – Ihrem Immobilienpartner mit Weitblick, Verlässlichkeit und Leidenschaft.</p>';
    public string $service_introtext_en = '<p>The quality of our work, the concrete benefit and the genuine interest in the concerns of our customers – whether private homeowners, condominium associations, entrepreneurs, family offices or institutional investors – are at the center of our actions. We not only stand for professional property management, but also for personal support, sound advice and the sustainable increase in value of the properties entrusted to us.</p><p>We think outside the box, take responsibility and go the extra mile – so that the value of your property is not only preserved, but grows in the long term.</p><p>Our greatest joy is to master challenges and find tailor-made solutions for you. Because in the end, only one thing counts for us: your satisfaction.</p><p>With <em>be real</em> we have created a brand that combines the tried and tested with innovation. For us, it\'s not just about managing properties – we want to actively shape and develop them together with you.</p><p>Welcome to be real – your property partner with foresight, reliability and passion.</p>';

    public string $contact_header_de = 'in touch';
    public string $contact_header_en = 'in touch';

    public string $contact_subheader_de = 'Wir freuen uns auf Ihre Anfrage!';
    public string $contact_subheader_en = 'We look forward to your inquiry!';

    public string $contact_introtext_de = '<p>Sie haben Fragen zu unseren Leistungen, möchten ein individuelles Angebot oder ein persönliches Beratungsgespräch vereinbaren? Dann sind Sie hier genau richtig. Unser Team von be real Immobilienmanagement steht Ihnen gerne zur Verfügung – kompetent, verlässlich und persönlich. Wir freuen uns darauf, von Ihnen zu hören!</p>';
    public string $contact_introtext_en = '<p>Do you have questions about our services, would you like to receive an individual offer or arrange a personal consultation? Then you\'ve come to the right place. Our team at be real Immobilienmanagement is at your disposal – competent, reliable and personal. We look forward to hearing from you!</p>';

    public string $reference_header_de = 'modern';
    public string $reference_header_en = 'modern';

    public string $reference_subheader_de = 'Kontinuität, Leidenschaft & Innovation';
    public string $reference_subheader_en = 'Continuity, Passion & Innovation';

    public string $reference_introtext_de = '<p>be real vereint das Beste aus zwei Welten: die soliden, bewährten Werte der Vergangenheit und den frischen, innovativen Wind, der durch den Generationswechsel und die Neupositionierung in das Unternehmen eingebracht wurde. Aber der Name ist weit mehr als nur die Initialen der Eigentümer und einer Ableitung des Wortes „Realitäten". be real steht für unsere Art zu arbeiten: nah am Kunden, mit echtem, unverfälschtem Service und jeder Menge Herzblut.&nbsp;</p>';
    public string $reference_introtext_en = '<p>be real combines the best of two worlds: the solid, tried and tested values of the past and the fresh, innovative spirit that has been brought into the company through the generational change and repositioning. But the name is far more than just the initials of the owners and a derivation of the word "Realitäten" (properties). be real stands for our way of working: close to the customer, with genuine, unadulterated service and lots of passion.&nbsp;</p>';

    public string $competence_header_de = 'competent';
    public string $competence_header_en = 'competent';

    public string $competence_subheader_de = 'Unsere Leistungen';
    public string $competence_subheader_en = 'Our Services';

    public string $competence_introtext_de = '<p>Hausverwaltung ist für uns mehr als ein Auftrag – sie ist unsere Leidenschaft und unser täglicher Anspruch. Ob Haus- oder Miteigentum, Wohnungseigentumsverwaltung, Gewerbeimmobilien, Bauverwaltung oder individuelle Beratung, wir kümmern uns zuverlässig darum, dass Sie sich rundum gut betreut fühlen. Im Mittelpunkt unserer Arbeit stehen partnerschaftliche Zusammenarbeit, maßgeschneiderte Lösungen und eine persönliche, umfassende Beratung</p>';
    public string $competence_introtext_en = '<p>Property management is more than just an assignment for us – it is our passion and our daily standard. Whether house or joint ownership, condominium management, commercial properties, building management or individual consulting, we reliably ensure that you feel well looked after all around. At the center of our work are partnership cooperation, tailor-made solutions and personal, comprehensive advice</p>';

    public static function group(): string
    {
        return 'translatable_hausverwaltung_landingpage';
    }
}
