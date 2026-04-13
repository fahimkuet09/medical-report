<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;

class ReportPdfService
{
    /**
     * Render a report as PDF bytes (mPDF).
     */
    public function render(Report $report): string
    {
        $html = view('admin.reports.pdf', ['report' => $report])->render();

        $tempDir = storage_path('framework/mpdf');
        if (! File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'default_font' => 'dejavusans',
            'tempDir' => $tempDir,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }
}
