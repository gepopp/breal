<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicAssetsExistTest extends TestCase
{
    public function test_every_file_a_view_or_class_loads_with_asset_exists_in_public(): void
    {
        $missing = [];

        $files = collect(File::allFiles(resource_path('views')))
            ->merge(File::allFiles(app_path()))
            ->filter(fn ($file): bool => str_ends_with($file->getFilename(), '.php'));

        foreach ($files as $file) {
            preg_match_all('/asset\(\s*[\'"]([^\'"{$]+)[\'"]\s*\)/', $file->getContents(), $matches);

            foreach ($matches[1] as $path) {
                if (! File::exists(public_path($path))) {
                    $missing[$path] = $file->getFilename();
                }
            }
        }

        $this->assertSame([], $missing, 'These files are loaded with asset() but do not exist in public/');
    }
}
