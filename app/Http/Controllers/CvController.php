<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Services\CvPdfExporter;
use App\Support\GuestIdentity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CvController extends Controller
{
    public function store(Request $request, GuestIdentity $guestIdentity): RedirectResponse
    {
        $cv = new Cv(['title' => 'Untitled CV']);

        if ($request->user()) {
            $cv->user_id = $request->user()->id;
        } else {
            $cv->session_id = $guestIdentity->resolve($request);
        }

        $cv->save();

        return redirect()->route('cv.edit', $cv);
    }

    public function preview(Cv $cv): View
    {
        return view($cv->templateView(), ['cv' => $cv]);
    }

    public function export(Cv $cv, CvPdfExporter $exporter): Response
    {
        $pdf = $exporter->render($cv);

        $filename = Str::slug($cv->title ?: 'cv').'.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
