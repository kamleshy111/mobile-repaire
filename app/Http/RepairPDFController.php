<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Brand;

class RepairPDFController extends Controller
{
    public function downloadPdf(Request $request)
    {

        $repairId = $request->input('repair_id');

        $repair = Repair::select('repairs.*')->where('repairs.id', $repairId)->first();

        if (!$repair) {
            return response()->json(['error' => 'Repair not found'], 404);
        }

        $pdf = Pdf::loadView('pdf.repairs', ['repair' => $repair]);


        return Response::streamDownload(
            fn () => print($pdf->output()), 
            'repair-' . $repair->id . '.pdf'
        );
    }
}
