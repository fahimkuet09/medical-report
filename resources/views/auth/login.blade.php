<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin login — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <div class="mb-8 flex flex-col items-center text-center">
            <img
                src="https://bmc-bd.org/images/logo.png"
                alt="Bangladesh Medical College"
                class="mb-4 h-16 w-auto object-contain"
                width="200"
                height="64"
                onerror="this.style.display='none'"
            >
            <h1 class="text-xl font-semibold text-slate-900">Bangladesh Medical College</h1>
            <p class="mt-1 text-sm text-slate-600">ECHO 2D — Admin sign in</p>
        </div>

        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <form method="post" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 shadow-sm focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 shadow-sm focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"
                    >
                </div>
                <div class="flex items-center gap-2">
                    <input id="remember" name="remember" type="checkbox" value="1" class="rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                    <label for="remember" class="text-sm text-slate-600">Remember me</label>
                </div>
                <button type="submit" class="w-full rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">
                    Sign in
                </button>
            </form>
        </div>
        <p class="mt-8 text-center text-xs text-slate-500">
            <a href="https://bmc-bd.org/" class="text-teal-700 hover:underline" target="_blank" rel="noopener">bmc-bd.org</a>
        </p>
    </div>
</body>
</html>
