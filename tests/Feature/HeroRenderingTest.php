<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HeroRenderingTest extends TestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function heroViews(): array
    {
        return [
            'hausverwaltung hero' => ['livewire/parts/landing-hero.blade.php'],
            'technik hero' => ['livewire/parts/technik-hero.blade.php'],
        ];
    }

    /**
     * AOS hides every element with data-aos until the script has run and its delay has passed; on the hero this
     * delayed the largest contentful paint by about two seconds.
     */
    #[DataProvider('heroViews')]
    public function test_the_hero_is_not_hidden_behind_an_aos_animation(string $view): void
    {
        $this->assertStringNotContainsString('data-aos', file_get_contents(resource_path('views/'.$view)));
    }

    public function test_the_parallax_image_is_a_webp_of_sensible_size(): void
    {
        $path = public_path('team-parallax.webp');

        $this->assertFileExists($path);
        $this->assertLessThan(150 * 1024, filesize($path));
        $this->assertFileDoesNotExist(public_path('team-parallax.jpg'));
        $this->assertStringContainsString('team-parallax.webp', file_get_contents(resource_path('views/livewire/parts/service-v2.blade.php')));
    }
}
