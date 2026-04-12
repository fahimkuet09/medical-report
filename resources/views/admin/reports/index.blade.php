@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between print:hidden">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">ECHO 2D reports</h1>
            <!-- <p class="mt-1 text-sm text-slate-600">Latest reports first. Search by cardio ID, refresh from the external API, or add remarks.</p> -->
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                id="btn-refresh-reports"
                data-url="{{ route('api.reports.refresh') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-teal-800 disabled:opacity-60"
            >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7m0 0L19.5 9.348m-4.992 0v4.992" />
                </svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <form method="get" action="{{ route('admin.reports.index') }}" class="mb-6 flex flex-wrap items-end gap-3 print:hidden">
        <div class="min-w-[200px] flex-1">
            <label for="cardio_id" class="mb-1 block text-sm font-medium text-slate-700">Search cardio ID</label>
            <input
                id="cardio_id"
                name="cardio_id"
                type="text"
                value="{{ $search }}"
                placeholder="e.g. CARD-1024"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"
            >
        </div>
        <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            Search
        </button>
        @if ($search !== '')
            <a href="{{ route('admin.reports.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-teal-800 hover:underline">Clear</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Cardio ID</th>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Age</th>
                        <th class="px-4 py-3">Sex</th>
                        <th class="px-4 py-3">Patient ID</th>
                        <th class="px-4 py-3">Date received</th>
                        <th class="px-4 py-3">Date delivery</th>
                        <th class="px-4 py-3">Ward / cabin</th>
                        <th class="px-4 py-3 text-right print:hidden">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reports as $report)
                        <tr class="hover:bg-slate-50/80">
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-900">{{ $report->cardio_id }}</td>
                            <td class="px-4 py-3 text-slate-900">{{ $report->patient_name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $report->age }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $report->sex }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $report->patient_id }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $report->date_received?->format('d M Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $report->date_delivery?->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $report->ward_cabin ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right print:hidden">
                                <a
                                    href="{{ route('admin.reports.show', $report) }}"
                                    class="mr-2 inline-flex rounded-md border border-slate-300 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                >
                                    Preview
                                </a>
                                <a
                                    href="{{ route('admin.reports.pdf', $report) }}"
                                    class="mr-2 inline-flex rounded-md border border-teal-600 bg-teal-50 px-2.5 py-1 text-xs font-medium text-teal-800 hover:bg-teal-100"
                                >
                                    PDF
                                </a>
                                <button
                                    type="button"
                                    data-open-remarks
                                    data-report-id="{{ $report->id }}"
                                    data-remarks='@json($report->remarks)'
                                    class="inline-flex rounded-md bg-slate-800 px-2.5 py-1 text-xs font-medium text-white hover:bg-slate-900"
                                >
                                    Remarks
                                </button>
                            </td>
                        </tr>
                    @empty
                        <!-- <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-500">
                                No reports yet. Configure <code class="rounded bg-slate-100 px-1 py-0.5 text-xs">ECHO_REPORT_API_URL</code> and press <strong>Refresh</strong>.
                            </td>
                        </tr> -->
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($reports->hasPages())
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3 print:hidden">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    {{-- Remarks modal --}}
    <div
        id="remarks-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 print:hidden"
        aria-hidden="true"
        role="dialog"
        aria-labelledby="remarks-modal-title"
    >
        <div class="max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-xl border border-slate-200 bg-white p-6 shadow-xl sm:p-8">
            <div class="mb-4 flex items-start justify-between gap-4">
                <h2 id="remarks-modal-title" class="text-lg font-semibold text-slate-900">Remarks</h2>
                <button type="button" data-close-remarks class="rounded p-1 text-slate-500 hover:bg-slate-100 hover:text-slate-800" aria-label="Close">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="remarks-form" data-base-url="{{ url('/admin/reports/__ID__/remarks') }}">
                @csrf
                <input type="hidden" id="remarks-report-id" name="report_id" value="">
                <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                    <label for="remarks-editor" class="text-sm font-medium text-slate-700">Clinical / administrative remarks</label>
                    <div class="flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 p-0.5">
                        <span class="hidden pl-2 text-xs text-slate-500 sm:inline">Text size</span>
                        <button
                            type="button"
                            id="remarks-size-down"
                            class="rounded-md px-2.5 py-1 text-sm font-semibold text-slate-700 hover:bg-white hover:shadow-sm"
                            title="Decrease size"
                            aria-label="Decrease text size"
                        >
                            A−
                        </button>
                        <button
                            type="button"
                            id="remarks-size-up"
                            class="rounded-md px-2.5 py-1 text-sm font-semibold text-slate-700 hover:bg-white hover:shadow-sm"
                            title="Increase size"
                            aria-label="Increase text size"
                        >
                            A+
                        </button>
                    </div>
                </div>
                <div class="remarks-editor-shell w-full overflow-hidden rounded-lg border border-slate-300 bg-white shadow-inner ring-teal-600/40 focus-within:border-teal-500 focus-within:ring-2">
                    <textarea id="remarks-editor" name="remarks" rows="14" class="min-h-[420px] w-full resize-y border-0 font-sans text-base leading-relaxed focus:ring-0"></textarea>
                </div>
                <p class="mt-2 text-xs text-slate-500">Saved to the database and shown on the printable report when present.</p>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" data-close-remarks class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" id="remarks-save" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                        Save remarks
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
    @vite(['resources/js/admin.js'])
@endpush
