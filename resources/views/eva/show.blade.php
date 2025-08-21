<x-layouts.app :title="'Detail EVA - ' . (optional($eva->project)->project_name ?? 'Proyek Tidak Ditemukan')">
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <p class="text-sm text-blue-600 font-semibold">Detail Analisis Nilai Hasil</p>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ optional($eva->project)->project_name ?? 'Proyek Tidak Ditemukan' }}
                </h1>
                <p class="mt-1 text-gray-500">
                    Laporan untuk <span class="font-medium">Minggu ke-{{ $eva->week_number }}</span> 
                    (Tanggal: <span class="font-medium">{{ $eva->report_date ? \Carbon\Carbon::parse($eva->report_date)->isoFormat('D MMMM YYYY') : '-' }}</span>)
                </p>
            </div>
            <a href="{{ route('eva.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Main Metrics Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Progress</p>
                <p class="text-2xl font-bold text-blue-600">{{ $eva->progress ?? 0 }}%</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Planned Value (PV)</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($eva->pv ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Earned Value (EV)</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($eva->ev ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <p class="text-sm font-medium text-gray-500">Actual Cost (AC)</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($eva->ac ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Performance Analysis -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Schedule Performance -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Kinerja Jadwal</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Schedule Variance (SV)</p>
                        <p class="text-xl font-bold {{ ($eva->sv ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($eva->sv ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(($eva->sv ?? 0) >= 0)
                                ✅ Proyek berjalan lebih cepat dari jadwal yang direncanakan.
                            @else
                                ❌ Proyek mengalami keterlambatan dari jadwal.
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Schedule Performance Index (SPI)</p>
                        <p class="text-xl font-bold {{ ($eva->spi ?? 0) >= 1 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($eva->spi ?? 0, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(($eva->spi ?? 0) >= 1)
                                ✅ Efisiensi waktu proyek baik (nilai ≥ 1).
                            @else
                                ❌ Efisiensi waktu proyek buruk, pekerjaan lebih lambat dari rencana (nilai < 1).
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Cost Performance -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Kinerja Biaya</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Cost Variance (CV)</p>
                        <p class="text-xl font-bold {{ ($eva->cv ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($eva->cv ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(($eva->cv ?? 0) >= 0)
                                ✅ Biaya yang dikeluarkan lebih rendah dari anggaran (hemat).
                            @else
                                ❌ Biaya yang dikeluarkan melebihi anggaran (boros).
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cost Performance Index (CPI)</p>
                        <p class="text-xl font-bold {{ ($eva->cpi ?? 0) >= 1 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($eva->cpi ?? 0, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(($eva->cpi ?? 0) >= 1)
                                ✅ Penggunaan biaya sangat efisien (nilai ≥ 1).
                            @else
                                ❌ Penggunaan biaya tidak efisien, pengeluaran lebih besar dari nilai pekerjaan (nilai < 1).
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Forecast -->
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Prediksi Proyek</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Budget at Completion (BAC)</p>
                    <p class="text-xl font-semibold text-gray-800">Rp {{ number_format($eva->bac ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total anggaran awal proyek.</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estimate at Completion (EAC)</p>
                    <p class="text-xl font-semibold text-gray-800">Rp {{ number_format($eva->eac ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Prediksi total biaya proyek saat selesai.</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Variance at Completion (VAC)</p>
                    <p class="text-xl font-bold {{ ($eva->vac ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($eva->vac ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if(($eva->vac ?? 0) >= 0)
                            ✅ Proyek diprediksi akan selesai dengan sisa anggaran.
                        @else
                            ❌ Proyek diprediksi akan selesai melebihi anggaran.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($eva->notes)
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Catatan</h3>
            <div class="text-base text-gray-700 whitespace-pre-wrap prose max-w-none">
                {{ $eva->notes }}
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>
