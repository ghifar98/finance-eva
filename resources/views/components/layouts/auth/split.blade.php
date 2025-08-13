<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="min-h-screen antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center lg:grid-cols-2">
            <!-- Left Panel (Luxury Branding) -->
            <div class="relative hidden h-full flex-col p-12 lg:flex">
                <!-- Background Elements -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-950 to-blue-900">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-orange-500/30 blur-3xl"></div>
                        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 rounded-full bg-blue-600/30 blur-3xl"></div>
                    </div>
                </div>
                
                <!-- Branding -->
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-2xl font-semibold text-white" wire:navigate>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 me-3 p-2 shadow-lg">
                        <x-app-logo-icon class="h-7 fill-current text-white" />
                    </div>
                    {{ config('app.name', 'YourBrand') }}
                </a>

                <!-- Inspirational Quote -->
                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto space-y-4">
                    <blockquote class="space-y-4">
                        <p class="text-2xl font-light leading-relaxed text-white/90">&ldquo;{{ trim($message) }}&rdquo;</p>
                        <footer class="text-lg font-medium text-orange-400">{{ trim($author) }}</footer>
                    </blockquote>
                </div>
            </div>

            <!-- Right Panel (Login Content) -->
            <div class="flex w-full items-center justify-center p-8 lg:p-12">
                <div class="mx-auto w-full max-w-md">
                    <!-- Mobile Branding -->
                    <a href="{{ route('home') }}" class="z-20 mb-8 flex flex-col items-center gap-3 font-medium lg:hidden" wire:navigate>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 p-2 shadow-lg">
                            <x-app-logo-icon class="h-7 fill-current text-white" />
                        </div>
                        <span class="sr-only">{{ config('app.name', 'YourBrand') }}</span>
                    </a>

                    <!-- Content Card -->
                    <div class="rounded-xl border border-white/10 bg-white/5 backdrop-blur-lg shadow-xl overflow-hidden">
                        <div class="h-1 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                        <div class="p-8 text-white">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @fluxScripts
    </body>
</html>