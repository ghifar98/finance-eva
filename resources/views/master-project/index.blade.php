<x-layouts.app :title="__('Master Project')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Header dan Tombol Aksi -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Master Project</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar lengkap proyek yang sedang berjalan</p>
            </div>
            <div>
                <x-button 
                    href="{{ route('master-projects.create') }}" 
                    positive 
                    icon="plus" 
                    label="Tambah Project" 
                />
            </div>
        </div>

        <!-- Statistik Cepat -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-stat-card 
                title="Total Project" 
                value="{{ $totalProjects }}" 
                icon="folder" 
            />
            <x-stat-card 
                title="Dalam Pengerjaan" 
                value="{{ $totalProjects }}" 
                icon="clock" 
                color="yellow" 
            />
            <x-stat-card 
                title="Selesai" 
                value="{{ $completedProjects }}" 
                icon="check-circle" 
                color="green" 
            />
        </div>

        <!-- Proyek Aktif -->
        <div class="space-y-4">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                <x-icon name="arrow-path" class="mr-2 inline h-5 w-5 text-yellow-500" />
                Proyek Sedang Berjalan
            </h2>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <livewire:master-project-table :projects="$activeProjects" />
            </div>
        </div>

        <!-- Proyek Selesai -->
        <div class="space-y-4">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                <x-icon name="check-badge" class="mr-2 inline h-5 w-5 text-green-500" />
                Proyek Selesai
            </h2>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <livewire:master-project-table
                    :projects="$completedProjects" 
                    :showCompleted="true"
                />
            </div>
        </div>
    </div>
</x-layouts.app>