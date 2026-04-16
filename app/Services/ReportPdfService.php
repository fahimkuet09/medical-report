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

        $topMm = (int) config('echo_reports.letterhead_top_mm', 40);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => $topMm,
            'margin_bottom' => 12,
            'default_font' => 'dejavusans',
            'tempDir' => $tempDir,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }
}
