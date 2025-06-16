<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Statistik Utama -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Card Total Proyek -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Proyek</p>
                        <h3 class="mt-1 text-3xl font-bold">24</h3>
                        <p class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            12% dari bulan lalu
                        </p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card Proyek Berjalan -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Proyek Berjalan</p>
                        <h3 class="mt-1 text-3xl font-bold">15</h3>
                        <div class="mt-3 flex items-center">
                            <div class="mr-2 h-2 w-full rounded-full bg-neutral-200 dark:bg-neutral-700">
                                <div class="h-2 rounded-full bg-yellow-500" style="width: 65%"></div>
                            </div>
                            <span class="text-sm">65%</span>
                        </div>
                    </div>
                    <div class="rounded-lg bg-amber-100 p-3 dark:bg-amber-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card Pembelian Terakhir -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Pembelian Terakhir</p>
                        <h3 class="mt-1 text-3xl font-bold">Rp 28,5jt</h3>
                        <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                            Vendor: PT. Jaya Abadi
                        </p>
                    </div>
                    <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dua Kolom Bawah -->
        <div class="grid flex-1 gap-4 md:grid-cols-2">
            <!-- Proyek Terbaru -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <h3 class="font-medium">Proyek Terbaru</h3>
                </div>
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach($recentProjects as $project)
                    <div class="p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium">{{ $project->project_name }}</h4>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $project->vendor }} • 
                                    <span class="text-amber-600 dark:text-amber-400">{{ $project->progress }}%</span>
                                </p>
                            </div>
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $project->kode_project }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span>{{ \Carbon\Carbon::parse($project->start_project)->format('d M Y') }} - 
                                  {{ \Carbon\Carbon::parse($project->end_project)->format('d M Y') }}</span>
                            <span class="font-medium">Rp {{ number_format($project->nilai, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-neutral-200 px-6 py-3 text-center dark:border-neutral-700">
                    <a href="{{ route('master-projects.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Lihat Semua Proyek
                    </a>
                </div>
            </div>

            <!-- Grafik Progress Tahunan -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium">Progress Tahunan</h3>
                        <select class="rounded-lg border border-neutral-200 bg-transparent px-3 py-1 text-sm dark:border-neutral-700">
                            <option>2023</option>
                            <option selected>2024</option>
                        </select>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="annualProgressChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Progress Tahunan
            const ctx = document.getElementById('annualProgressChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Nilai Proyek',
                        data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Pembelian',
                        data: [8, 15, 12, 18, 20, 25, 22, 30, 28, 35, 32, 40],
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value + 'jt';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>