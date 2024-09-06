<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RepairPDFController extends Controller
{
    public function downloadPdf(Request $request)
    {

        $repairIds = $request->input('repair_ids', []);

        $repairs = Repair::whereIn('id', $repairIds)->get();

        $pdf = Pdf::loadView('pdf.repairs', ['repairs' => $repairs]);

        return Response::streamDownload(fn () => print($pdf->output()), 'repairs.pdf');
    }
}
