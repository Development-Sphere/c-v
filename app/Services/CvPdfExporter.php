<?php

namespace App\Services;

use App\Models\Cv;
use Spatie\Browsershot\Browsershot;

class CvPdfExporter
{
    public function render(Cv $cv): string
    {
        $html = view($cv->templateView(), ['cv' => $cv])->render();

        $browsershot = Browsershot::html($html)
            ->setChromePath(config('services.browsershot.chrome_path'))
            ->format('A4')
            ->showBackground();

        if (config('services.browsershot.no_sandbox')) {
            $browsershot->noSandbox();
        }

        return $browsershot->pdf();
    }
}
