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
    <body class="min-h-screen bg-gradient-to-br from-blue-950 to-blue-900 antialiased">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 overflow-hidden opacity-15">
            <div class="absolute top-20 -left-20 w-72 h-72 rounded-full bg-orange-500/10 blur-3xl"></div>
            <div class="absolute bottom-20 -right-20 w-72 h-72 rounded-full bg-blue-600/10 blur-3xl"></div>
        </div>

        <div class="relative flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md z-10">
                <!-- Brand logo/icon -->
                <a href="{{ route('home') }}" class="flex flex-col items-center mb-6" wire:navigate>
                    <div class="flex items-center justify-center h-16 w-16 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 p-2 shadow-lg">
                        <x-app-logo-icon class="size-8 fill-current text-white" />
                    </div>
                    <span class="sr-only">{{ config('app.name', 'YourBrand') }}</span>
                </a>

                <!-- Main card container -->
                <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl overflow-hidden">
                    <!-- Accent header strip -->
                    <div class="h-1.5 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                    
                    <!-- Content area -->
                    <div class="p-8 text-white">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer note -->
        <div class="absolute bottom-6 w-full text-center text-sm text-blue-300/50">
            &copy; {{ date('Y') }} {{ config('app.name', 'YourBrand') }}. All rights reserved.
        </div>

        @fluxScripts
    </body>
</html>