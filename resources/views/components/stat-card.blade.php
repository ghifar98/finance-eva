@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => null,
    'trendDirection' => 'up'
])

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'dark' => 'dark:bg-blue-900/50 dark:text-blue-400'],
        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'dark' => 'dark:bg-green-900/50 dark:text-green-400'],
        'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'dark' => 'dark:bg-yellow-900/50 dark:text-yellow-400'],
        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'dark' => 'dark:bg-red-900/50 dark:text-red-400'],
    ];
@endphp

<div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <h3 class="mt-1 text-2xl font-bold">{{ $value }}</h3>
            @if($trend)
                <p class="mt-1 flex items-center text-sm {{ $trendDirection === 'up' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    @if($trendDirection === 'up')
                        <x-icon name="arrow-trending-up" class="mr-1 h-4 w-4" />
                    @else
                        <x-icon name="arrow-trending-down" class="mr-1 h-4 w-4" />
                    @endif
                    {{ $trend }}
                </p>
            @endif
        </div>
        <div class="rounded-lg p-3 {{ $colors[$color]['bg'] }} {{ $colors[$color]['dark'] }}">
            <x-icon name="{{ $icon }}" class="h-6 w-6 {{ $colors[$color]['text'] }}" />
        </div>
    </div>
</div>