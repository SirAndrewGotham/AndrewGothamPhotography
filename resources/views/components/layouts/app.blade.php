<?php
// Dark/light theme + language switcher in header
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Andrew Gotham Photography' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Prevent FOUC -->
    <script>
        if (localStorage.getItem('theme') === 'light' ||
            (!('theme' in localStorage) && !window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stage-950 text-content-primary antialiased min-h-screen flex flex-col">

<!-- Header -->
<header class="fixed top-0 left-0 right-0 bg-stage-950/80 backdrop-blur-md border-b border-stage-700 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-display font-bold text-content-primary">
                        Andrew <span class="text-spotlight-500">Gotham</span> Photography
                    </span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-content-secondary hover:text-content-primary transition-colors {{ request()->routeIs('home') ? 'text-content-primary' : '' }}">
                    {{ __('nav.home') }}
                </a>
                <a href="{{ route('albums') }}" class="text-content-secondary hover:text-content-primary transition-colors {{ request()->routeIs('albums*') ? 'text-content-primary' : '' }}">
                    {{ __('nav.portfolio') }}
                </a>
                <a href="{{ route('contact') }}" class="text-content-secondary hover:text-content-primary transition-colors {{ request()->routeIs('contact') ? 'text-content-primary' : '' }}">
                    {{ __('nav.contact') }}
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-3">
                <!-- Language Switcher -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        @click.away="open = false"
                        class="flex items-center gap-2 px-3 py-2 text-content-secondary hover:text-content-primary transition-colors rounded-lg hover:bg-stage-800"
                    >
                        <span class="text-sm font-medium uppercase">{{ app()->getLocale() }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-32 bg-stage-900 border border-stage-700 rounded-lg shadow-lg py-1 z-50"
                    >
                        <a href="{{ route('lang.switch', 'ru') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-stage-800 {{ app()->getLocale() === 'ru' ? 'text-spotlight-500' : '' }}">Русский</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-stage-800 {{ app()->getLocale() === 'en' ? 'text-spotlight-500' : '' }}">English</a>
                        <a href="{{ route('lang.switch', 'eo') }}" class="block px-4 py-2 text-sm text-content-secondary hover:text-content-primary hover:bg-stage-800 {{ app()->getLocale() === 'eo' ? 'text-spotlight-500' : '' }}">Esperanto</a>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button
                    x-data="{
                            dark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                            toggle() {
                                this.dark = !this.dark;
                                localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                                document.documentElement.classList.toggle('dark', this.dark);
                            }
                        }"
                    x-init="$watch('dark', val => document.documentElement.classList.toggle('dark', val))"
                    @click="toggle()"
                    class="w-10 h-10 rounded-full bg-stage-800 hover:bg-stage-700 flex items-center justify-center text-content-secondary hover:text-content-primary transition-colors"
                    aria-label="{{ __('common.toggle_theme') }}"
                >
                    <!-- Sun -->
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <!-- Moon -->
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-content-secondary hover:text-content-primary" @click="$dispatch('toggle-mobile-menu')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (Alpine) -->
    <div
        x-data="{ open: false }"
        @toggle-mobile-menu.window="open = !open"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-stage-700 bg-stage-950"
    >
        <nav class="px-4 py-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-content-secondary hover:text-content-primary hover:bg-stage-800 rounded-lg transition-colors">
                {{ __('nav.home') }}
            </a>
            <a href="{{ route('albums') }}" class="block px-4 py-3 text-content-secondary hover:text-content-primary hover:bg-stage-800 rounded-lg transition-colors">
                {{ __('nav.portfolio') }}
            </a>
            <a href="{{ route('contact') }}" class="block px-4 py-3 text-content-secondary hover:text-content-primary hover:bg-stage-800 rounded-lg transition-colors">
                {{ __('nav.contact') }}
            </a>
        </nav>
    </div>
</header>

<!-- Main Content -->
<main class="flex-1 pt-16">
    {{ $slot }}
</main>

<!-- Footer -->
<footer class="border-t border-stage-700 py-8">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-content-muted text-sm">
            &copy; {{ date('Y') }} Andrew Gotham Photography. {{ __('footer.rights') }}
        </p>
        <p class="text-content-muted text-xs mt-2">
            {{ __('footer.legal') }}
        </p>
    </div>
</footer>

@livewireScripts
@stack('scripts')
</body>
</html>
