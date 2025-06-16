<x-layouts.app :title="__('Vendor')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Tombol Tambah Vendor --}}
        <x-button href="{{ route('vendor.create') }}" light positive label="Tambah Vendor" />

        {{-- Tabel Vendor --}}
        <livewire:vendor-table />
    </div>
</x-layouts.app>
