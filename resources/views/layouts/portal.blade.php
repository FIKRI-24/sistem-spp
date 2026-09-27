<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100 dark:bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}Portal Pembayaran Siswa - {{ config('app.name', 'SIPS SPP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 flex flex-col" x-data="{ mobileNavOpen: false }">
    <!-- Portal Header / Navbar -->
    <header class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Branding -->
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-indigo-700 font-bold text-lg shadow-sm">
                        SPP
                    </div>
                    <div>
                        <span class="block text-base font-bold tracking-tight">Portal Siswa & Wali</span>
                        <span class="block text-xs text-indigo-200">Sistem Informasi Pembayaran Sekolah</span>
                    </div>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden sm:flex items-center gap-4">
                    <span class="text-xs bg-indigo-800/80 text-indigo-100 px-3 py-1.5 rounded-full border border-indigo-500/30">
                        NIS: <strong>{{ auth()->user()->nis ?? '12345' }}</strong> &bull; {{ auth()->user()->name ?? 'Siswa' }}
                    </span>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('portal-logout-form').submit();"
                       class="text-xs font-semibold bg-indigo-900/60 hover:bg-indigo-900 text-white px-3.5 py-1.5 rounded-lg border border-indigo-400/30 transition">
                        Keluar
                    </a>
                    <form id="portal-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>

                <!-- Mobile Hamburger -->
                <div class="sm:hidden">
                    <button type="button" @click="mobileNavOpen = !mobileNavOpen" class="p-2 text-indigo-100 hover:text-white rounded-lg">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileNavOpen" class="sm:hidden border-t border-indigo-600 bg-indigo-800 px-4 pt-3 pb-4 space-y-2" style="display: none;">
            <div class="text-xs text-indigo-200 pb-2 border-b border-indigo-700">
                Login sebagai: <strong class="text-white">{{ auth()->user()->name ?? 'Siswa' }}</strong>
            </div>
            <a href="#" class="block py-2 text-sm font-medium text-white hover:text-indigo-200">
                Tagihan Saya
            </a>
            <a href="#" class="block py-2 text-sm font-medium text-white hover:text-indigo-200">
                Histori Pembayaran
            </a>
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('portal-logout-form').submit();"
               class="block py-2 text-sm font-medium text-red-200 hover:text-red-100">
                Keluar
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Message -->
        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-950/30 dark:text-emerald-300 shadow-sm flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-800/40 dark:bg-rose-950/30 dark:text-rose-300 shadow-sm flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @isset($header)
            <div class="mb-6">
                {{ $header }}
            </div>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Portal Footer -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Portal Pembayaran Siswa &bull; {{ config('app.name', 'SIPS SPP') }}
    </footer>
</body>
</html>
