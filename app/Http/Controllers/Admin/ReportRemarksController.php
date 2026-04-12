<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReportRemarksRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class ReportRemarksController extends Controller
{
    public function update(UpdateReportRemarksRequest $request, Report $report): JsonResponse
    {
        $report->update([
            'remarks' => $request->validated('remarks'),
        ]);

        return (new ReportResource($report->fresh()))
            ->response()
            ->setStatusCode(200);
    }
}
