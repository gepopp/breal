<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TranslationKeysTest extends TestCase
{
    public function test_german_translation_file_is_valid_json(): void
    {
        $translations = json_decode(file_get_contents(lang_path('de.json')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertIsArray($translations);
        $this->assertNotEmpty($translations);
    }

    public function test_every_translation_key_used_in_code_exists_in_the_german_file(): void
    {
        $german = json_decode(file_get_contents(lang_path('de.json')), true, flags: JSON_THROW_ON_ERROR);
        $groups = $this->germanPhpGroups();

        $missing = [];

        foreach ($this->usedTranslationKeys() as $key => $file) {
            if (array_key_exists($key, $german)) {
                continue;
            }

            [$group, $item] = array_pad(explode('.', $key, 2), 2, null);

            if ($item !== null && isset($groups[$group]) && Arr::has($groups[$group], $item)) {
                continue;
            }

            $missing[] = "{$key} ({$file})";
        }

        $this->assertSame([], $missing, "Missing keys in lang/de.json:\n".implode("\n", $missing));
    }

    /**
     * @return array<string, string> key => first file using it
     */
    private function usedTranslationKeys(): array
    {
        $keys = [];

        foreach (['app', 'resources/views', 'routes'] as $directory) {
            foreach (File::allFiles(base_path($directory)) as $file) {
                $relative = str_replace('\\', '/', $file->getRelativePathname());

                if (! str_ends_with($relative, '.php') || str_starts_with($relative, 'vendor/') || str_contains($relative, 'email/template')) {
                    continue;
                }

                preg_match_all(
                    '/(?:__|trans|@lang|Lang::get)\(\s*([\'"])((?:(?!\1).)+)\1(?!\s*\.)/',
                    $file->getContents(),
                    $matches
                );

                foreach ($matches[2] as $key) {
                    if (str_contains($key, '::') || str_contains($key, '$') || str_contains($key, '{') || preg_match('/\s/', $key)) {
                        continue;
                    }

                    $keys[$key] ??= "{$directory}/{$relative}";
                }
            }
        }

        return $keys;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function germanPhpGroups(): array
    {
        $groups = [];

        foreach (glob(lang_path('de/*.php')) as $file) {
            $groups[basename($file, '.php')] = include $file;
        }

        return $groups;
    }
}
