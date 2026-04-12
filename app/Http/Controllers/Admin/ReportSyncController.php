<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EchoReportSyncService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ReportSyncController extends Controller
{
    public function refresh(EchoReportSyncService $syncService): JsonResponse
    {
        try {
            $result = $syncService->syncFromRemote();
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Reports refreshed.',
            'inserted' => $result['inserted'],
            'skipped' => $result['skipped'],
            'total_in_response' => $result['total_in_response'],
        ]);
    }
}
