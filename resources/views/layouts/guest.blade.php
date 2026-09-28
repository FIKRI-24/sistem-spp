<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk — {{ config('app.name', 'SIPS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div class="text-center">
                <a href="/" class="inline-flex flex-col items-center gap-2">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-xl shadow-lg shadow-indigo-600/30">
                        SPP
                    </div>
                    <div>
                        <span class="block text-lg font-bold text-slate-900 dark:text-white">SIPS</span>
                        <span class="block text-xs text-slate-500 dark:text-slate-400">Sistem Informasi Pembayaran Sekolah</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-slate-400 dark:text-slate-500">
                &copy; {{ date('Y') }} SIPS &bull; Sistem keamanan aktif
            </p>
        </div>
    </body>
</html>
