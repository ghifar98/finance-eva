<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <x-button href="{{route('master-projects.create')}}" light positive label="Tambah" />
            <livewire:master-project-table/>
    </div>
</x-layouts.app>
