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
    <body class="min-h-screen antialiased bg-gradient-to-br from-blue-950 to-blue-900">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-orange-500/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 rounded-full bg-blue-600/20 blur-3xl"></div>
        </div>

        <div class="relative flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md z-10">
                <!-- Luxurious card container -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                    <!-- Accent bar -->
                    <div class="h-1.5 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                    
                    <div class="p-8">
                        <!-- Branding (optional) -->
                        <a href="{{ route('home') }}" class="flex justify-center mb-6" wire:navigate>
                            <span class="text-2xl font-bold bg-gradient-to-r from-orange-400 to-amber-400 bg-clip-text text-transparent">
                                {{ config('app.name', 'YourBrand') }}
                            </span>
                        </a>

                        <!-- Content slot -->
                        <div class="flex flex-col gap-6 text-white">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer note -->
        <div class="absolute bottom-4 w-full text-center text-sm text-blue-300/50">
            &copy; {{ date('Y') }} {{ config('app.name', 'YourBrand') }}. All rights reserved.
        </div>

        @fluxScripts
    </body>
</html>