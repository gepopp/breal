<?php

namespace App\Services;

use App\Models\Competence;
use App\Models\Realty;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Tells pages that existed once and are gone for good (answered with 410 instead of 404, so search engines drop
 * them sooner): properties that left the feed and the placeholder services of the early test content.
 */
class GonePages
{
    /**
     * Slugs of the lorem ipsum services that were indexed before the launch and are removed.
     *
     * @var array<int, string>
     */
    public const COMPETENCE_SLUGS = [
        'quis-soluta-eveniet-ut',
        'mollitia-ut-consequatur-aut-qui-hic-fuga-debitis',
        'aut-laborum-sit-dolores-assumenda-fugiat-libero',
        'tenetur-exercitationem-tempore-facere-natus-commodi-occaecati',
        'sed-facere-pariatur-hic-tenetur-optio-error-eos',
    ];

    public static function isGone(NotFoundHttpException $exception, Request $request): bool
    {
        $previous = $exception->getPrevious();

        if (! $previous instanceof ModelNotFoundException) {
            return false;
        }

        return match ($previous->getModel()) {
            Realty::class => true,
            Competence::class => in_array($request->route('competence'), self::COMPETENCE_SLUGS, true),
            default => false,
        };
    }
}
