<x-layouts.app title="{{ __('Dashboard') }}">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Cost Performance Index (CPI)</p>
                        <h3 class="mt-1 text-3xl font-bold">{{ number_format($latestEva->cpi ?? 0, 2) }}</h3>
                        @if(isset($latestEva))
                            <p class="mt-2 flex items-center text-sm {{ ($latestEva->cpi >= 1) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                @if($latestEva->cpi >= 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                    Under Budget
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17l-5-5m0 0l5-5m-5 5h12" /></svg>
                                    Over Budget
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0 1H9.5M12 6h2.5m-5 12v-1m0 1h2.5m-2.5 0H7.5M12 18h2.5M12 18v-1M4.732 10.268A5.98 5.98 0 014 12c0 1.657.672 3.157 1.757 4.243M19.268 13.732A5.98 5.98 0 0120 12c0-1.657-.672-3.157-1.757-4.243" /></svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Schedule Performance Index (SPI)</p>
                        <h3 class="mt-1 text-3xl font-bold">{{ number_format($latestEva->spi ?? 0, 2) }}</h3>
                         @if(isset($latestEva))
                            <p class="mt-2 flex items-center text-sm {{ ($latestEva->spi >= 1) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                @if($latestEva->spi >= 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                    Ahead of Schedule
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17l-5-5m0 0l5-5m-5 5h12" /></svg>
                                    Behind Schedule
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="rounded-lg bg-amber-100 p-3 dark:bg-amber-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Proyek</p>
                        <h3 class="mt-1 text-3xl font-bold">{{ $totalProjects ?? 0 }}</h3>
                        <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                            {{ $ongoingProjects ?? 0 }} Proyek Berjalan
                        </p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <div class="flex items-center justify-between">
                 
            

        <div class="grid flex-1 gap-4 md:grid-cols-2">
             <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <h3 class="font-medium">Proyek Terbaru</h3>
                </div>
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($recentProjects ?? [] as $project)
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
                    @empty
                    <div class="p-6 text-center text-neutral-500">
                        Tidak ada proyek terbaru.
                    </div>
                    @endforelse
                </div>
                <div class="border-t border-neutral-200 px-6 py-3 text-center dark:border-neutral-700">
                    <a href="{{ route('master-projects.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" wire:navigate>
                        Lihat Semua Proyek
                    </a>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <h3 class="font-medium">Detail EVA Proyek Terakhir</h3>
                    @if(isset($latestEva))
                    <p class="text-sm text-neutral-500">{{ $latestEva->project->project_name ?? 'N/A' }} - Minggu ke {{ $latestEva->week_number ?? 'N/A' }}</p>
                    @endif
                </div>
                @if(isset($latestEva))
                <div class="grid grid-cols-2 gap-px divide-x divide-y divide-neutral-200 bg-neutral-50 dark:divide-neutral-700 dark:bg-neutral-900/50">
                    <div class="p-4">
                        <p class="text-sm text-neutral-500">Schedule Variance (SV)</p>
                        <p class="font-semibold text-lg">Rp {{ number_format($latestEva->sv ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-neutral-500">Cost Variance (CV)</p>
                        <p class="font-semibold text-lg">Rp {{ number_format($latestEva->cv ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-neutral-500">Estimate at Completion (EAC)</p>
                        <p class="font-semibold text-lg">Rp {{ number_format($latestEva->eac ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-neutral-500">Variance at Completion (VAC)</p>
                        <p class="font-semibold text-lg">Rp {{ number_format($latestEva->vac ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="border-t border-neutral-200 p-4 dark:border-neutral-700">
                    <p class="text-sm text-neutral-500">Budget at Completion (BAC)</p>
                    <p class="font-semibold text-lg">Rp {{ number_format($latestEva->bac ?? 0, 0, ',', '.') }}</p>
                </div>
                @else
                <div class="p-6 text-center text-neutral-500">
                    Tidak ada data EVA untuk ditampilkan.
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', function() {
           
            const ctx = document.getElementById('annualEvaChart');
            if (!ctx) return;

            const existingChart = Chart.getChart(ctx);
            if (existingChart) {
                existingChart.destroy();
            }

            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Planned Value (PV)',
                        data: chartData.pv,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Earned Value (EV)',
                        data: chartData.ev,
                        backgroundColor: 'rgba(245, 158, 11, 0.7)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Actual Cost (AC)',
                        data: chartData.ac,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000) + 'jt';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
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
