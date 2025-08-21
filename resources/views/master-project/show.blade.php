<x-layouts.app :title="__('Detail Project') . ' - ' . $project->project_name">
    <div class="container mx-auto py-8 px-4">
        <!-- Header Proyek -->
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

        <!-- Form Progres -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow mb-6">
            <div class="bg-indigo-600 text-white p-4 rounded-t-xl">
                <h2 class="text-xl font-bold">Progres Proyek</h2>
                <p class="text-indigo-200 mt-1">Update perkembangan proyek harian/mingguan</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700">
                <form action="{{ route('project-progress.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Update</label>
                            <input type="date" name="progress_date" class="w-full px-3 py-2 border rounded-lg"
                                   value="{{ date('Y-m-d') }}" required
                                   @if($active_week_group)
                                       min="{{ $active_week_group['start_date'] }}"
                                       max="{{ $active_week_group['end_date'] }}"
                                   @elseif($project->start_project && $project->end_project)
                                       min="{{ $project->start_project }}"
                                       max="{{ $project->end_project }}"
                                   @endif>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jenis Update</label>
                            <select name="type" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="weekly">Mingguan</option>
                                <option value="milestone">Milestone</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Progres (%)</label>
                           <input type="text" name="progress_value" 
       pattern="^\d{1,3}([,\.]\d{1,2})?$" 
       class="w-full px-3 py-2 border rounded-lg"
       placeholder="cth: 3,98" required>
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

       <!-- Riwayat Progres per Minggu -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden mb-6">
    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
        <h2 class="text-lg font-semibold">Riwayat Progres per Minggu</h2>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
        @if($project->start_project && $project->end_project)
            @forelse($weekly_groups as $week)
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-lg font-semibold">Minggu {{ $week['week'] }}</span>
                            <span class="ml-2 text-sm text-gray-500">
                                ({{ \Carbon\Carbon::parse($week['start_date'])->format('d M') }} - 
                                {{ \Carbon\Carbon::parse($week['end_date'])->format('d M Y') }})
                            </span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">
                            Total: {{ $week['total_progress'] }}% 
                            @if($week['max_progress'] > 0 && $week['total_progress'] != $week['max_progress'])
                                (Maks: {{ $week['max_progress'] }}%)
                            @endif
                        </span>
                    </div>

                    @if($week['progress']->isNotEmpty())
                        <div class="mt-3 space-y-2">
                            @foreach($week['progress'] as $progress)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">
                                        {{ $progress->progress_date->format('d M') }}: 
                                        {{ $progress->description ?: 'Progres mingguan' }}
                                    </span>
                                    <span class="font-medium">{{ $progress->progress_value }}%</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" 
                             style="width: {{ $week['total_progress'] }}%"></div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">Tidak ada minggu yang terdefinisi untuk proyek ini</div>
            @endforelse
        @else
            <div class="p-6 text-center text-gray-500">Proyek ini belum memiliki tanggal mulai dan selesai yang valid</div>
        @endif
    </div>
</div>
        <!-- Detail Proyek -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Detail Kontrak</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">No Kontrak</span>
                        <span class="font-medium">{{ $project->kontrak }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Nilai Kontrak</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($project->nilai, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Tahun</span>
                        <span class="font-medium">{{ $project->tahun }}</span>
                    </div>
                </div>
            </div>

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

        <!-- Tombol Aksi -->
        <div class="flex justify-between">
            <a href="{{ route('master-projects.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-lg font-medium transition">
                
            </a>
            <a href="{{ route('master-projects.edit', $project->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                Edit Proyek
            </a>
        </div>
    </div>
         <!-- Tombol Aksi -->
<div class="flex justify-between">
    <a href="{{ route('master-projects.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-lg font-medium transition">
        &larr; Kembali ke Daftar
    </a>
   
    @if($project->data_proyek)
        <a href="{{ route('project.document.stream', $project->id) }}" target="_blank"
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
            Lihat Dokumen
        </a>
    @endif
</div>
            </div>
        </div>

    <x-errors class="mt-4" />
</x-layouts.app>