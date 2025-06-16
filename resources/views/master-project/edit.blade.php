<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <form action="{{ route('master-projects.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-card>
        {{-- WireUI component to display all form validation errors --}}
        <x-errors class="mb-4" /> 

        {{-- Grid container for two columns on medium screens and up --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            {{-- Column 1 Fields --}}
            <div>
                {{-- Kode Project --}}
                <x-input
                    name="kode_project"
                    label="Kode Project :"
                    placeholder="e.g., PRJ-001"
                    wire:model.live="kode_project" {{-- Bind to Livewire property --}}
                />
            </div>

            <div>
                {{-- Nama Projek --}}
                <x-input
                    name="project_name"
                    label="Nama Projek :"
                    placeholder="e.g., Pengembangan Aplikasi Internal"
                    wire:model.live="project_name"
                />
            </div>

            <div>
                {{-- Vendor --}}
                <x-input
                    name="vendor"
                    label="Vendor :"
                    placeholder="e.g., PT. Solusi Digital Indonesia"
                    wire:model.live="vendor"
                />
            </div>

            <div>
                {{-- Tahun --}}
                <x-input
                    name="tahun"
                    label="Tahun :"
                    placeholder="e.g., 2025"
                    type="number"
                    min="1900"
                    max="{{ date('Y') + 5 }}"
                    wire:model.live="tahun"
                />
            </div>

            <div>
                {{-- No Kontak --}}
                <x-input
                    name="kontrak"
                    label="No Kontak :"
                    placeholder="e.g., 081234567890"
                    type="tel"
                    wire:model.live="kontrak"
                />
            </div>

            {{-- Column 2 Fields --}}
            <div>
                {{-- Nilai (Currency) --}}
                <x-currency
                    name="nilai"
                    label="Nilai"
                    placeholder="e.g., 150.000.000"
                    prefix="RP"
                    thousands="."
                    decimal=","
                    precision="0"
                    wire:model.live="nilai" {{-- Use wire:model.live for real-time currency formatting --}}
                />
            </div>

            <div>
                {{-- Start Project Date Picker --}}
                <x-datetime-picker 
                    name="start_project"
                    wire:model.live="start_project" {{-- IMPORTANT: Bind to 'start_project' property --}}
                    label="Start Project"
                    placeholder="Pilih Tanggal Mulai"
                    parse-format="YYYY-MM-DD" {{-- Format for internal processing/database --}}
                    display-format="DD/MM/YYYY" {{-- Format for user display --}}
                />
            </div>

            <div>
                {{-- End Project Date Picker --}}
                <x-datetime-picker
                    name="end_project"
                    wire:model.live="end_project" {{-- IMPORTANT: Bind to 'end_project' property --}}
                    label="End Project"
                    placeholder="Pilih Tanggal Akhir"
                    parse-format="YYYY-MM-DD"
                    display-format="DD/MM/YYYY"
                />
            </div>

            <div>
                {{-- Asal Kode --}}
                <x-input
                    name="asal_kode"
                    label="Asal Kode :"
                    placeholder="e.g., GitHub, Local"
                    wire:model.live="asal_kode"
                />
            </div>
        </div> {{-- End of grid container --}}

        {{-- Submit Button (WireUI button) --}}
        <div class="mt-6 flex justify-end"> {{-- Added margin-top for spacing and justify-end to align button right --}}
            <x-button type="submit" primary label="Submit Data" spinner="store" /> {{-- spinner="store" shows loading state --}}
        </div>
    </x-card>
        </form>
        
    </div>
</x-layouts.app>
