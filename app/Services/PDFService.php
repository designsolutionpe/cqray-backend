<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use PDF;

class PDFService
{
    public static function generateVoucher($voucherType,$filename,$data)
    {
        $pdf = PDF::loadView("pdf.voucher.$voucherType",$data)->setPaper("letter","landscape");

        Storage::disk('public')->put("$filename.pdf", $pdf->output());

        return Storage::url($filename);
    }
}
