@extends('layouts.admin')

@section('html_title', 'ECHO 2D Report')

@push('head')
    <style>
        .echo-report {
            font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Helvetica, Arial, sans-serif;
            color: #000;
            background: #fff;
        }
        .echo-report .echo-title {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .echo-report .echo-patient-box {
            border: 1px solid #000;
            padding: 1rem 1.15rem 1.2rem;
        }
        .echo-report .echo-label {
            font-weight: 700;
        }
        .echo-report .echo-patient-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .echo-report .echo-patient-table td {
            vertical-align: top;
            padding: 0.28rem 0.35rem;
        }
        .echo-report .echo-patient-table .echo-col-left {
            width: 36%;
            text-align: left;
        }
        .echo-report .echo-patient-table .echo-col-mid {
            width: 28%;
            text-align: center;
        }
        .echo-report .echo-patient-table .echo-col-right {
            width: 36%;
            text-align: right;
        }
        .echo-report .echo-remarks-heading {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
        }
        .echo-report .echo-remarks-block {
            margin-top: 1.1rem;
        }
        .echo-report .echo-remarks-body-tight p { margin-top: 0; margin-bottom: 0.35rem; }
        .echo-report .echo-remarks-body-tight p:last-child { margin-bottom: 0; }
        .echo-report .echo-remarks-body-tight ol,
        .echo-report .echo-remarks-body-tight ul { margin: 0.25rem 0 0.35rem; padding-left: 1.1rem; }
        .echo-report .echo-remarks-body-tight li { margin-bottom: 0.2rem; }
        .echo-report .echo-remarks-body-tight table { font-size: 0.9rem; }
        .echo-report .echo-remarks-body-tight th,
        .echo-report .echo-remarks-body-tight td { padding: 0.2rem 0.35rem; }
        @media print {
            @page {
                margin: 10mm 10mm 12mm 10mm;
            }
            body {
                background: #fff !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .echo-report-wrap {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .echo-report {
                padding: 0.35in 0.4in 0.45in !important;
            }
            .echo-report header.mb-6 { margin-bottom: 0.75rem !important; }
            .echo-report .echo-remarks-block {
                margin-top: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="no-print mb-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-start sm:justify-between">
        <a href="{{ route('admin.reports.index') }}" class="text-sm font-medium text-teal-800 hover:underline">← Back to list</a>
        <div class="flex max-w-md flex-col gap-2 sm:items-end">
            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ route('admin.reports.pdf', $report) }}"
                    class="inline-flex w-fit items-center gap-2 rounded-lg border border-teal-700 bg-teal-700 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-800"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    Download PDF
                </a>
                <!-- <button type="button" onclick="window.print()" class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.53 21.326a.75.75 0 0 0 1.06 1.06L12 18.77l4.47 4.616a.75.75 0 0 0 1.06-1.06l-1.75-9.5a.75.75 0 0 0-.43-.615A48.12 48.12 0 0 0 12 9c-2.392 0-4.68.122-6.77.346m13.24 12.03a48.109 48.109 0 0 1-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.576 51.576 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    Print
                </button> -->
            </div>
            <!-- <p class="text-xs leading-snug text-slate-500">
                To hide the page address and date in the printed copy, open the print dialog and turn off <strong>Headers and footers</strong> (Chrome/Edge: More settings → uncheck Headers and footers).
            </p> -->
        </div>
    </div>

    <article class="echo-report-wrap mx-auto max-w-4xl rounded-xl border border-slate-200 bg-white p-8 shadow-sm print:rounded-none print:border-0 print:shadow-none">
        <div class="echo-report px-1 py-2 sm:px-2">
            <header class="mb-6 text-center">
                <h1 class="echo-title text-black">ECHO 2D Report</h1>
            </header>

            {{-- Bordered box: 5 rows × 3 columns — rows line up (Patient Id top center) --}}
            <div class="echo-patient-box">
                <table class="echo-patient-table" role="presentation">
                    <tbody>
                        <tr>
                            <td class="echo-col-left">
                                <span class="echo-label">Cardio. Id :</span>
                                <span> {{ $report->cardio_id }}</span>
                            </td>
                            <td class="echo-col-mid">
                                <span class="echo-label">Patient Id :</span>
                                <span> {{ $report->patient_id }}</span>
                            </td>
                            <td class="echo-col-right">
                                <span class="echo-label">Date Received :</span>
                                <span> {{ $report->date_received?->format('d/m/Y') ?? '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="echo-col-left">
                                <span class="echo-label">Patient's Name :</span>
                                <span> {{ $report->patient_name }}</span>
                            </td>
                            <td class="echo-col-mid"></td>
                            <td class="echo-col-right">
                                <span class="echo-label">Date Delivery :</span>
                                <span> {{ $report->date_delivery?->format('d/m/Y') ?? '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="echo-col-left">
                                <span class="echo-label">Age :</span>
                                <span> {{ $report->age }}</span>
                            </td>
                            <td class="echo-col-mid"></td>
                            <td class="echo-col-right">
                                <span class="echo-label">Sex :</span>
                                <span> {{ $report->sex }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="echo-col-left">
                                <span class="echo-label">Referred By :</span>
                                <span> {{ $report->referred_by ?? '' }}</span>
                            </td>
                            <td class="echo-col-mid"></td>
                            <td class="echo-col-right">
                                <span class="echo-label">Ward / Cabin :</span>
                                <span> {{ $report->ward_cabin ?? '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="echo-col-left">
                                <span class="echo-label">Specimen :</span>
                                <span> {{ $report->specimen ?? '' }}</span>
                            </td>
                            <td class="echo-col-mid"></td>
                            <td class="echo-col-right">
                                <span class="echo-label">Bed :</span>
                                <span> {{ $report->bed ?? '' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if (! empty($report->remarks))
                <div class="echo-remarks-block text-black">
                    <p class="echo-remarks-heading">Remarks :</p>
                    <div class="echo-remarks-body-tight max-w-none text-[0.92rem] leading-snug text-black">
                        {!! $report->remarks !!}
                    </div>
                </div>
            @endif

            <footer class="no-print mt-10 border-t border-slate-200 pt-4 text-center text-xs text-slate-500">
                <p>System record — {{ config('app.name') }} · Created {{ $report->created_at?->format('d/m/Y H:i') }} · Updated {{ $report->updated_at?->format('d/m/Y H:i') }}</p>
            </footer>
        </div>
    </article>
@endsection
