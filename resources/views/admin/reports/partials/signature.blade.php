@php
    $lines = config('echo_reports.signature_lines', []);
@endphp
@if (count($lines))
    <div class="echo-signature">
        @foreach ($lines as $i => $line)
            <div class="echo-signature-line @if ($i === 0) echo-signature-name @endif">{{ $line }}</div>
        @endforeach
    </div>
@endif
