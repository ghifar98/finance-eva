<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PT. INDERA SAE PRATAMA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite('resources/css/app.css')
</head>
<body class="antialiased">

    {{-- Background Gradient (lebih soft supaya logo kontras) --}}
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-700 via-blue-500 to-blue-400 relative overflow-hidden">

        {{-- Ornamen halus --}}
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-orange-400 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-300 rounded-full blur-3xl opacity-30"></div>

        {{-- Header Branding --}}
        <div class="mb-8 text-center">
            <img src="{{ asset('logo.png') }}" alt="PT. INDERA SAE PRATAMA Logo" class="mx-auto h-20 mb-4">
            <h1 class="text-4xl font-extrabold text-white tracking-wide">PT. INDERA SAE PRATAMA</h1>
            <p class="mt-2 text-lg text-gray-100">Visualize and Create with Your Reliable Partner</p>
        </div>

        {{-- Card --}}
        <div class="max-w-md w-full bg-white/30 backdrop-blur-md border border-white/40 rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-blue-900 text-center mb-4">Selamat Datang 👋</h2>
            <p class="text-gray-800 text-center mb-6">Kelola proyek dengan lebih mudah & transparan</p>

            {{-- Tombol --}}
            <div class="flex gap-4">
                <a href="{{ route('login') }}"
                   class="flex-1 px-6 py-3 rounded-xl bg-orange-500 text-white font-semibold text-center shadow-md hover:bg-orange-600 transition">
                   Login
                </a>
                
            </div>
        </div>

        {{-- Footer --}}
        <footer class="absolute bottom-6 text-sm text-gray-200 text-center">
            © {{ date('Y') }} PT. INDERA SAE PRATAMA. All rights reserved.
        </footer>
    </div>
</body>
</html>
