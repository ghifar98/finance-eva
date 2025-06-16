

<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <form action="{{ route('generalleadger.index') }}" method="GET" class="flex items-center gap-4">
                <x-datetime-picker
                    wire:model.live="start_date"
                    label="Tanggal Mulai"
                    placeholder="Select start date"
                    without-time
                    name="start_date"
                />
                <x-datetime-picker
                    wire:model.live="end_date"
                    label="Tanggal Akhir"
                    placeholder="Select end date"
                    without-time
                    name="end_date"
                />
                <x-button type="submit" positive label="Filter" />
                <x-button href="{{ route('generalleadger.index') }}" light label="Reset" />
            </form>
        </div>
            
           <livewire:generalleadger-table startDate="{{$startDate}}" endDate="{{$endDate}}"/>
    </div>
</x-layouts.app>
