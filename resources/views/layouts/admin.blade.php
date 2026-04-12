<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('html_title')@yield('html_title')@else@yield('title', 'Admin') — {{ config('app.name') }}@endif</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white shadow-sm print:hidden">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3">
                <img
                    src="https://bmc-bd.org/images/logo.png"
                    alt="Bangladesh Medical College"
                    class="h-12 w-auto max-w-[200px] object-contain"
                    width="180"
                    height="48"
                    onerror="this.style.display='none'"
                >
                <div class="leading-tight">
                    <p class="text-xs font-medium uppercase tracking-wide text-teal-800">Bangladesh Medical College</p>
                    <p class="text-lg font-semibold text-slate-900">ECHO 2D Reports</p>
                </div>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->email }}</span>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Log out
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 print:max-w-none print:px-0">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-900 print:hidden">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
