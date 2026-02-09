<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Epaper;
use Illuminate\Http\Request;

class EpaperController extends Controller
{
    /**
     * Show list of epapers
     */
    public function index(Request $request)
{
    $query = Epaper::query();

    // Agar user ne date choose ki ho
    if ($request->publication_date) {
        $query->whereDate('publication_date', $request->publication_date);
    }

    $epapers = $query->orderBy('publication_date', 'desc')->get();

    return view('custom.epaper', compact('epapers'));
}


   public function show(Epaper $epaper)
{
    $pdf = $epaper->pdf_file 
        ? route('pdf.view', $epaper->pdf_file->id)
        : null;

    $cover = $epaper->cover_image 
        ? $epaper->cover_image->getUrl()
        : null;

    return view('custom.epaper-detail', compact('epaper', 'pdf', 'cover'));
}



}
