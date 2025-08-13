<x-layouts.app :title="'Detail EVA - ' . ($eva->project->project_name ?? 'Proyek Tidak Ditemukan')">
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">
            Detail EVA - {{ $eva->project->project_name ?? 'Proyek Tidak Ditemukan' }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded shadow">
            <div>
                <p class="text-sm text-gray-500">Minggu ke</p>
                <p class="text-lg font-semibold">{{ $eva->week_number ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Laporan</p>
                <p class="text-lg font-semibold">
                    {{ $eva->report_date ? \Carbon\Carbon::parse($eva->report_date)->format('d M Y') : '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Progress</p>
                <p class="text-lg font-semibold">{{ $eva->progress ?? 0 }}%</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">BAC</p>
                <p class="text-lg font-semibold">
                    Rp {{ number_format($eva->bac ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Planned Value (PV)</p>
                <p class="text-lg font-semibold">
                    Rp {{ number_format($eva->pv ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Earned Value (EV)</p>
                <p class="text-lg font-semibold">
                    Rp {{ number_format($eva->ev ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Actual Cost (AC)</p>
                <p class="text-lg font-semibold">
                    Rp {{ number_format($eva->ac ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Schedule Performance Index (SPI)</p>
                <p class="text-lg font-semibold">
                    {{ number_format($eva->spi ?? 0, 2) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Cost Performance Index (CPI)</p>
                <p class="text-lg font-semibold">
                    {{ number_format($eva->cpi ?? 0, 2) }}
                </p>
            </div>
        </div>

        @if($eva->notes)
        <div class="mt-6">
            <p class="text-sm text-gray-500">Catatan:</p>
            <p class="text-base text-gray-800">{{ $eva->notes }}</p>
        </div>
        @endif
    </div>
</x-layouts.app>
