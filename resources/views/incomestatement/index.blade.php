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
                    label="project"
                    :options="$projects"
                    placeholder="Pilih mata uang"
                    :selected="old('currency')"
                    option-label="project_name" option-value="id"/>
                <x-button type="submit" positive label="Filter" />
                <x-button href="{{ route('incomestatement.index') }}" light label="Reset" />
            </form>
        </div>

        <livewire:incomestatement-table startDate="{{ $startDate }}" endDate="{{ $endDate }}" 
        projectId="{{ $projectIdSelected }}"
        />

        <div class="flex justify-center">
            <x-card>
                <h1>
                    total </h1>
                <h1 class="text-2xl font-bold">
                    {{ $amount }}
                </h1>
            </x-card>

        </div>
    
    </div>
</x-layouts.app>
