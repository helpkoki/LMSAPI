<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PolicyController extends Controller
{
    public function show()
    {
        return view('policies');
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('policies_pdf');
        return $pdf->download('company_leave_policies.pdf');
    }
}
