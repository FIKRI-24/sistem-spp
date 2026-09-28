<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'SIPS SPP') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:400,600,700,800,900,900i|space-mono:400,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100" x-data="{ sidebarOpen: false, profileDropdownOpen: false }">
    <div class="min-h-full flex">
        <!-- Off-canvas mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Sidebar for Mobile -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 px-6 pb-4 flex flex-col lg:hidden"
             style="display: none;">
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-lg shadow-md shadow-indigo-600/30">
                        SPP
                    </div>
                    <div>
                        <span class="block text-base font-bold text-white tracking-wide">SIPS Admin</span>
                        <span class="block text-xs text-slate-400">Pembayaran Sekolah</span>
                    </div>
                </div>
                <button type="button" @click="sidebarOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @include('layouts.partials.admin-sidebar-nav')
        </div>

        <!-- Static Sidebar for Desktop -->
        <aside class="hidden lg:fixed lg:inset-y-0 lg:z-30 lg:flex lg:w-64 lg:flex-col border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex h-16 shrink-0 items-center gap-3 px-6 border-b border-slate-200 dark:border-slate-800">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-lg shadow-md shadow-indigo-600/30">
                    SPP
                </div>
                <div>
                    <span class="block text-base font-bold text-slate-900 dark:text-white tracking-tight">SIPS Admin</span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400">Sistem Pembayaran SPP</span>
                </div>
            </div>
            @include('layouts.partials.admin-sidebar-nav')
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur px-4 sm:px-6 lg:px-8">
                <!-- Mobile menu toggle -->
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-slate-700 dark:text-slate-300 lg:hidden">
                    <span class="sr-only">Buka menu sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Breadcrumb or App Title -->
                <div class="flex flex-1 items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-2.5 py-1 rounded-full border border-indigo-200/50 dark:border-indigo-800/50">
                        {{ config('app.env') === 'local' ? 'Dev Environment' : 'Production' }}
                    </span>
                </div>

                <!-- Right Nav Items -->
                <div class="flex items-center gap-x-4">
                    <!-- User Profile Dropdown -->
                    <div class="relative" @click.outside="profileDropdownOpen = false">
                        <button type="button" 
                                @click="profileDropdownOpen = !profileDropdownOpen" 
                                class="flex items-center gap-3 p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-left transition">
                            <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-semibold text-sm text-slate-700 dark:text-slate-200">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden md:block pr-1">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white leading-none">
                                    {{ auth()->user()->name ?? 'Petugas SPP' }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ auth()->user()->email ?? 'admin@sekolah.sch.id' }}
                                </p>
                            </div>
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileDropdownOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 z-30 mt-2 w-52 origin-top-right rounded-xl bg-white dark:bg-slate-800 py-2 shadow-lg ring-1 ring-slate-900/5 dark:ring-white/10 divide-y divide-slate-100 dark:divide-slate-700" 
                             style="display: none;">
                            <div class="px-4 py-2 text-xs text-slate-500 dark:text-slate-400">
                                Masuk sebagai: <strong class="text-slate-700 dark:text-slate-200">{{ auth()->user()->name ?? 'Admin' }}</strong>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    Profil Pengguna
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30">
                                        Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Notifications -->
            <div class="px-4 sm:px-6 lg:px-8 pt-4">
                @if (session('success'))
                    <div class="mb-4 flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-950/30 dark:text-emerald-300 shadow-sm" role="alert">
                        <svg class="h-5 w-5 shrink-0 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 flex items-center gap-3 rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-800/40 dark:bg-rose-950/30 dark:text-rose-300 shadow-sm" role="alert">
                        <svg class="h-5 w-5 shrink-0 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">{{ session('error') }}</div>
                    </div>
                @endif
            </div>

            <!-- Page Heading (if present) -->
            @isset($header)
                <div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 sm:px-6 lg:px-8 py-5">
                    {{ $header }}
                </div>
            @endisset

            <!-- Page Main Content Slot -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 sm:px-6 lg:px-8 py-4 text-xs text-slate-500 dark:text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-2">
                <span>&copy; {{ date('Y') }} SIPS - Sistem Informasi Pembayaran Sekolah.</span>
                <span>Laravel 13 &bull; PHP 8.4 &bull; MySQL</span>
            </footer>
        </div>
    </div>
</body>
</html>
