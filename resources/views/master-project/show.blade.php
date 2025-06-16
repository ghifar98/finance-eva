{{-- resources/views/master-project/show.blade.php --}}
<x-layouts.app :title="__('Detail Project') . ' - ' . $project->project_name">
    <div class="container mx-auto py-8 px-4">
        <!-- Project Header -->
        <div class="bg-indigo-600 text-white rounded-t-xl p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ $project->project_name }}</h1>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-3 py-1 bg-indigo-500 rounded-full text-sm">
                            {{ $project->kode_project }}
                        </span>
                        <span class="px-3 py-1 bg-indigo-500 rounded-full text-sm">
                            {{ $project->tahun }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-indigo-200">Dibuat oleh</p>
                    <div class="flex items-center justify-end gap-2 mt-1">
                        <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center">
                            <span class="text-indigo-700 font-bold text-sm">
                                {{ substr($project->user->name, 0, 1) }}
                            </span>
                        </div>
                        <span class="font-medium">{{ $project->user->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow mb-6">
            <div class="bg-indigo-600 text-white p-4 rounded-t-xl">
                <h2 class="text-xl font-bold">Progres Proyek</h2>
                <p class="text-indigo-200 mt-1">Update perkembangan proyek harian/mingguan</p>
            </div>
            
            <!-- Progress Form -->
            <div class="p-4 bg-gray-50 dark:bg-gray-700">
                <form action="{{ route('project-progress.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Update</label>
                            <input type="date" name="progress_date" class="w-full px-3 py-2 border rounded-lg" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-1">Jenis Update</label>
                            <select name="type" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="milestone">Milestone</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-1">Progres (%)</label>
                            <input type="number" name="progress_value" min="0" max="100" step="0.5" 
                                   class="w-full px-3 py-2 border rounded-lg" 
                                   placeholder="0-100" required>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                                Simpan Progres
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-1">Deskripsi Progres</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg" 
                                  placeholder="Aktivitas yang telah diselesaikan..."></textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Progress History -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden mb-6">
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
                <h2 class="text-lg font-semibold">Riwayat Progres</h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($project->progressEntries->sortByDesc('progress_date') as $progress)
                <div class="p-4">
                    <div class="flex justify-between">
                        <div class="flex items-center">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                {{ strtoupper($progress->type) }}
                            </span>
                            <span class="ml-2 text-sm font-medium">
                                {{ $progress->progress_date->format('d M Y') }}
                            </span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">
                            {{ $progress->progress_value }}%
                        </span>
                    </div>
                    
                    @if($progress->description)
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        {{ $progress->description }}
                    </p>
                    @endif
                    
                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress->progress_value }}%"></div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    Belum ada riwayat progres untuk proyek ini
                </div>
                @endforelse
            </div>
        </div>

        <!-- Project Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Informasi Proyek -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Proyek</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Kode Project</span>
                        <span class="font-medium">{{ $project->kode_project }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Vendor</span>
                        <span class="font-medium">{{ $project->vendor }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Status</span>
                        <span class="font-medium">
                            @if($project->progress >= 100)
                                <span class="text-green-600">Selesai (100%)</span>
                            @else
                                <span class="text-yellow-600">Berjalan ({{ $project->progress }}%)</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Detail Kontrak -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Detail Kontrak</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">No Kontrak</span>
                        <span class="font-medium">{{ $project->kontrak }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Nilai Kontrak</span>
                        <span class="font-medium text-green-600">
                            Rp {{ number_format($project->nilai, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Tahun</span>
                        <span class="font-medium">{{ $project->tahun }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Jadwal Proyek -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Jadwal Proyek</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Mulai</span>
                        <span class="font-medium">
                            {{ $project->start_project ? \Carbon\Carbon::parse($project->start_project)->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Selesai</span>
                        <span class="font-medium">
                            {{ $project->end_project ? \Carbon\Carbon::parse($project->end_project)->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Durasi</span>
                        <span class="font-medium">
                            @if($project->start_project && $project->end_project)
                                {{ \Carbon\Carbon::parse($project->start_project)->diffInDays($project->end_project) }} hari
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('master-projects.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-lg font-medium transition">
                &larr; Kembali ke Daftar
            </a>
            <div class="flex gap-3">
                <a href="{{ route('master-projects.edit', $project->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    Edit Proyek
                </a>
            </div>
        </div>
    </div>
    <x-errors class="mt-4" />
</x-layouts.app>