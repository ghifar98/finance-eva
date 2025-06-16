<x-layouts.app :title="__('Purchase')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Tombol Tambah Purchase --}}
        <x-button href="{{ route('purchase.create') }}" light positive label="Tambah Purchase" />

        {{-- Tabel Purchase --}}
        <livewire:purchase-table />
    </div>
</x-layouts.app>
