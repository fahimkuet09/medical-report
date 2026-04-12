<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ReportPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('cardio_id', ''));

        $query = Report::query()->orderByDesc('created_at');

        if ($search !== '') {
            $query->where('cardio_id', 'like', '%'.$search.'%');
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'search' => $search,
        ]);
    }

    public function show(Report $report): View
    {
        return view('admin.reports.show', [
            'report' => $report,
        ]);
    }

    public function pdf(Report $report, ReportPdfService $pdfService): Response
    {
        try {
            $binary = $pdfService->render($report);
        } catch (Throwable $e) {
            report($e);

            abort(500, 'Could not generate PDF.');
        }

        $safe = preg_replace('/[^A-Za-z0-9._-]+/', '-', $report->cardio_id) ?: 'report';
        $filename = 'ECHO-'.$safe.'.pdf';

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
