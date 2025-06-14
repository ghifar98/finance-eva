<x-layouts.app :title="__('Account')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <x-button href="{{route('account.create')}}" light positive label="Tambah" />
        <livewire:account-table/>
    </div>
</x-layouts.app>
