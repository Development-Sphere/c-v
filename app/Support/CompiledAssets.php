<?php

namespace App\Support;

/**
 * Reads compiled Vite asset contents directly off disk. Used by views that
 * Browsershot renders to HTML strings (the CV templates), so headless Chrome
 * never needs to fetch CSS back over HTTP from the app itself — on a
 * single-worker dev server (`php artisan serve`), that self-fetch deadlocks:
 * the PHP process is still blocked waiting on the Browsershot/Chrome child
 * process, so it can never answer Chrome's own CSS request.
 */
class CompiledAssets
{
    public static function css(string $entry = 'resources/css/app.css'): string
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $cssFile = $manifest[$entry]['file'] ?? null;

        if (! $cssFile || ! file_exists(public_path('build/'.$cssFile))) {
            return '';
        }

        return file_get_contents(public_path('build/'.$cssFile));
    }
}
