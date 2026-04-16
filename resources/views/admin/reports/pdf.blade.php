<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ECHO 2D Report</title>
    <style>
        body {
            font-family: dejavusans, sans-serif;
            font-size: 10pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .echo-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 0 0 8pt 0;
        }
        .echo-patient-box {
            border: 1px solid #000;
            padding: 7pt 8pt 8pt 8pt;
        }
        .echo-patient-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .echo-patient-table td {
            vertical-align: top;
            padding: 1.5pt 2pt;
            font-size: 10pt;
            line-height: 1.35;
        }
        .echo-col-left { width: 36%; text-align: left; }
        .echo-col-mid { width: 28%; text-align: center; }
        .echo-col-right { width: 36%; text-align: right; }
        .echo-label { font-weight: bold; }
        .echo-remarks-block { margin-top: 9pt; }
        .echo-remarks-heading { font-weight: bold; margin: 0 0 3pt 0; font-size: 10pt; }
        .echo-remarks-body { font-size: 10pt; line-height: 1.35; }
        .echo-remarks-body p { margin: 0 0 2.5pt 0; }
        .echo-remarks-body p:last-child { margin-bottom: 0; }
        .echo-remarks-body h1,
        .echo-remarks-body h2,
        .echo-remarks-body h3,
        .echo-remarks-body h4 {
            font-size: 10pt;
            font-weight: bold;
            margin: 5pt 0 2pt 0;
            line-height: 1.3;
        }
        .echo-remarks-body h1:first-child,
        .echo-remarks-body h2:first-child,
        .echo-remarks-body h3:first-child { margin-top: 0; }
        .echo-remarks-body ol,
        .echo-remarks-body ul {
            margin: 2pt 0 3pt 0;
            padding-left: 14pt;
        }
        .echo-remarks-body li { margin: 0 0 1.5pt 0; line-height: 1.35; }
        .echo-remarks-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 3pt 0;
            font-size: 9.5pt;
        }
        .echo-remarks-body th,
        .echo-remarks-body td {
            padding: 2pt 3pt;
            vertical-align: top;
            line-height: 1.3;
        }
    </style>
</head>
<body>
    <h1 class="echo-title">ECHO 2D Report</h1>

    <div class="echo-patient-box">
        <table class="echo-patient-table">
            <tbody>
                <tr>
                    <td class="echo-col-left">
                        <span class="echo-label">Cardio. Id :</span>
                        {{ $report->cardio_id }}
                    </td>
                    <td class="echo-col-mid">
                        <span class="echo-label">Patient Id :</span>
                        {{ $report->patient_id }}
                    </td>
                    <td class="echo-col-right">
                        <span class="echo-label">Date Received :</span>
                        {{ $report->date_received?->format('d/m/Y') ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="echo-col-left">
                        <span class="echo-label">Patient's Name :</span>
                        {{ $report->patient_name }}
                    </td>
                    <td class="echo-col-mid"></td>
                    <td class="echo-col-right">
                        <span class="echo-label">Date Delivery :</span>
                        {{ $report->date_delivery?->format('d/m/Y') ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="echo-col-left">
                        <span class="echo-label">Age :</span>
                        {{ $report->age }}
                    </td>
                    <td class="echo-col-mid"></td>
                    <td class="echo-col-right">
                        <span class="echo-label">Sex :</span>
                        {{ $report->sex }}
                    </td>
                </tr>
                <tr>
                    <td class="echo-col-left">
                        <span class="echo-label">Referred By :</span>
                        {{ $report->referred_by ?? '' }}
                    </td>
                    <td class="echo-col-mid"></td>
                    <td class="echo-col-right">
                        <span class="echo-label">Ward / Cabin :</span>
                        {{ $report->ward_cabin ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="echo-col-left">
                        <span class="echo-label">Specimen :</span>
                        {{ $report->specimen ?? '' }}
                    </td>
                    <td class="echo-col-mid"></td>
                    <td class="echo-col-right">
                        <span class="echo-label">Bed :</span>
                        {{ $report->bed ?? '' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if (! empty($report->remarks))
        <div class="echo-remarks-block">
            <p class="echo-remarks-heading">Remarks :</p>
            <div class="echo-remarks-body">{!! $report->remarks !!}</div>
        </div>
    @endif
</body>
</html>
