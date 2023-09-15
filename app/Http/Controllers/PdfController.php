<?php

namespace App\Http\Controllers;

use App\Models\VepostTracking;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function index(Request $request, $id)
    {
        $initialFile = VepostTracking::findOrFail($id);
        $mpID = $initialFile->mpID;

        $files = VepostTracking::where('mpID', $mpID)->get();
        $data = ['files' => $files];

        $pdf = PDF::loadView('file_transfer', $data);
        return $pdf->download('vepost_tracking.pdf');
    }

    public function view(Request $request, $id)
    {
        $initialFile = VepostTracking::findOrFail($id);
        $mpID = $initialFile->mpID;

        $files = VepostTracking::where('mpID', $mpID)->get();
        $data = ['files' => $files];

        $pdf = PDF::loadView('file_transfer', $data);
        return $pdf->stream('vepost_tracking.pdf');
    }
}
