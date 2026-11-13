<?php

namespace Tests\Feature;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

/**
 * Production installs without dev packages (`composer install --no-dev`). The app code must not use classes that only
 * come in through a dev package: laravel-lang/common pulls in dragon-code/*, which is not on the server.
 */
class ProductionDependenciesTest extends TestCase
{
    public function test_app_code_does_not_use_classes_of_dev_only_packages(): void
    {
        $offenders = [];

        foreach (['app', 'resources/views', 'routes', 'config', 'database', 'bootstrap/app.php'] as $path) {
            $path = base_path($path);

            $files = is_file($path)
                ? [$path]
                : array_map(
                    fn ($file) => $file->getPathname(),
                    iterator_to_array(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)))
                );

            foreach ($files as $file) {
                if (! str_ends_with($file, '.php')) {
                    continue;
                }

                if (str_contains((string) file_get_contents($file), 'DragonCode\\')) {
                    $offenders[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file);
                }
            }
        }

        $this->assertSame([], $offenders, 'These files use dragon-code classes, which are not installed in production.');
    }
}
