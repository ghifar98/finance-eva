<x-layouts.app :title="__('Income Statement')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <form action="{{ route('incomestatement.index') }}" method="GET" class="flex items-center gap-4">
                <x-datetime-picker
                    wire:model.live="start_date"
                    label="Tanggal Mulai"
                    placeholder="Pilih tanggal mulai"
                    without-time
                    name="start_date"
                />
                <x-datetime-picker
                    wire:model.live="end_date"
                    label="Tanggal Akhir"
                    placeholder="Pilih tanggal akhir"
                    without-time
                    name="end_date"
                />
                <x-select
                    name="project_id"
                    label="Project"
                    :options="$projects"
                    placeholder="Pilih Proyek"
                    :selected="$projectIdSelected"
                    option-label="project_name"
                    option-value="id"
                />
                <x-button type="submit" positive label="Filter" />
                <x-button href="{{ route('incomestatement.index') }}" light label="Reset" />
            </form>
        </div>

        {{-- Table PowerGrid --}}
      <livewire:incomestatement-table
    :startDate="$startDate ?? ''"
    :endDate="$endDate ?? ''"
    :projectId="$projectIdSelected ?? ''"
/>


        {{-- Summary Total --}}
<div class="flex justify-center mt-4">
    <x-card>
        <h1>Total:</h1>
        <h1 class="text-2xl font-bold">
            {{ 'Rp ' . number_format($amount, 0, ',', '.') }}
        </h1>
    </x-card>
</div>
{{-- Income Summary Breakdown --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
    <x-card class="text-center">
        <div class="text-sm text-gray-500">Gross Profit</div>
        <div class="text-xl font-bold text-green-600">
            {{ $calculatedTotals['gross_profit'] ?? 'Rp 0' }}
        </div>
    </x-card>
    <x-card class="text-center">
        <div class="text-sm text-gray-500">Operating Income</div>
        <div class="text-xl font-bold text-blue-600">
            {{ $calculatedTotals['operating_income'] ?? 'Rp 0' }}
        </div>
    </x-card>
    <x-card class="text-center">
        <div class="text-sm text-gray-500">Income Before Tax</div>
        <div class="text-xl font-bold text-yellow-600">
            {{ $calculatedTotals['income_before_tax'] ?? 'Rp 0' }}
        </div>
    </x-card>
    <x-card class="text-center">
        <div class="text-sm text-gray-500">Net Income</div>
        <div class="text-xl font-bold text-purple-600">
            {{ $calculatedTotals['other_income_balance'] ?? 'Rp 0' }}
        </div>
    </x-card>
</div>

        {{-- Grouped Account Summary --}}
        @if (!empty($grouped))
            <div class="mt-8 space-y-2">
                @foreach ($grouped as $code => $account)
                    @php
                        $highlightClasses = '';
                        // Kode akun untuk level header (highlight)
                        if (in_array(substr($code, 0, 2), ['42', '50'])) {
                            $highlightClasses = 'bg-red-500 text-white font-bold';
                        }
                    @endphp
                    <div class="flex justify-between items-center px-4 py-2 rounded {{ $highlightClasses }}">
                        <div class="text-left">
                            <div class="text-sm">{{ $code }}</div>
                            <div class="text-lg">{{ $account['name'] }}</div>
                        </div>
                        <div class="text-right text-lg font-semibold">
                            Rp {{ number_format($account['total'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 mt-4">Tidak ada data income statement ditemukan.</p>
        @endif
    </div>
</x-layouts.app>
