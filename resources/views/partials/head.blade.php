<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Wir sind Ihr verlässlicher Partner in Wien und ganz Österreich, wenn es um die ganzheitliche Abwicklung und Betreuung Ihrer Immobilien geht."/>
<title>{{ $title ?? 'bereal' }}</title>
<meta name="keywords" content="HTML, CSS, JavaScript">
<meta name="description" content="{{ $description ?? '' }}">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
@vite(['resources/js/app.js', 'resources/css/app.css'])
@fluxAppearance
@cookieconsentscripts
