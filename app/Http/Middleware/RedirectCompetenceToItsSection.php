<?php

namespace App\Http\Middleware;

use App\Enums\CompaniesEnum;
use App\Models\Competence;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A competence (Leistung) belongs to one section. Its address in the other
 * two sections is redirected permanently, so every Leistung exists only once.
 */
class RedirectCompetenceToItsSection
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $competence = $request->route('competence');

        if (is_string($competence)) {
            $competence = Competence::query()->where('slug', $competence)->first();
        }

        if (! $competence instanceof Competence) {
            return $next($request);
        }

        $expectedRoute = match ($competence->company) {
            CompaniesEnum::Makler => 'makler.leistung',
            CompaniesEnum::Technik => 'technik.leistung',
            default => 'hausverwaltung.leistung',
        };

        if ($request->route()->getName() === $expectedRoute) {
            return $next($request);
        }

        return redirect()->route($expectedRoute, $competence, 301);
    }
}
