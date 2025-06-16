<x-layouts.app :title="'Hasil EVA - Earned Value Analysis'">
    <div class="max-w-3xl mx-auto mt-10 space-y-6">

        <h2 class="text-xl font-bold">Hasil Perhitungan EVA</h2>

        <x-card>
            <ul class="space-y-2">
                <li><strong>Planned Value (PV):</strong> Rp {{ number_format($pv, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">Nilai rencana pekerjaan yang seharusnya selesai sampai minggu ini.</li>

                <li><strong>Earned Value (EV):</strong> Rp {{ number_format($ev, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">
                    Nilai pekerjaan yang benar-benar telah diselesaikan. 
                    @if($ev > $pv)
                        <span class="text-green-600">Lebih banyak dari rencana</span>
                    @elseif($ev == $pv)
                        <span class="text-blue-600">Sesuai rencana</span>
                    @else
                        <span class="text-red-600">Kurang dari rencana</span>
                    @endif
                </li>

                <li><strong>Actual Cost (AC):</strong> Rp {{ number_format($ac, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">Biaya aktual yang dikeluarkan hingga minggu ini.</li>

                <li><strong>Cost Variance (CV):</strong> Rp {{ number_format($cv, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">
                    @if($cv > 0)
                        <span class="text-green-600">Proyek lebih hemat dari anggaran</span>
                    @elseif($cv == 0)
                        <span class="text-blue-600">Tidak ada selisih biaya</span>
                    @else
                        <span class="text-red-600">Biaya melebihi dari nilai pekerjaan</span>
                    @endif
                </li>

                <li><strong>Schedule Variance (SV):</strong> Rp {{ number_format($sv, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">
                    @if($sv > 0)
                        <span class="text-green-600">Proyek lebih cepat dari jadwal</span>
                    @elseif($sv == 0)
                        <span class="text-blue-600">Proyek tepat waktu</span>
                    @else
                        <span class="text-red-600">Proyek terlambat dari jadwal</span>
                    @endif
                </li>

                <li><strong>Cost Performance Index (CPI):</strong> {{ number_format($cpi, 2) }}</li>
                <li class="text-sm text-gray-600">
                    @if($cpi > 1)
                        <span class="text-green-600">Kinerja biaya baik (hemat)</span>
                    @elseif($cpi == 1)
                        <span class="text-blue-600">Kinerja biaya sesuai anggaran</span>
                    @else
                        <span class="text-red-600">Kinerja biaya buruk (boros)</span>
                    @endif
                </li>

                <li><strong>Schedule Performance Index (SPI):</strong> {{ number_format($spi, 2) }}</li>
                <li class="text-sm text-gray-600">
                    @if($spi > 1)
                        <span class="text-green-600">Proyek berjalan lebih cepat</span>
                    @elseif($spi == 1)
                        <span class="text-blue-600">Proyek sesuai jadwal</span>
                    @else
                        <span class="text-red-600">Proyek tertinggal</span>
                    @endif
                </li>

                <li><strong>Estimate at Completion (EAC):</strong> Rp {{ number_format($eac, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">Estimasi total biaya proyek jika kinerja biaya saat ini berlanjut.</li>

                <li><strong>Estimate to Complete (ETC):</strong> Rp {{ number_format($etc, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">Estimasi biaya yang masih diperlukan untuk menyelesaikan proyek.</li>

                <li><strong>Variance at Completion (VAC):</strong> Rp {{ number_format($vac, 0, ',', '.') }}</li>
                <li class="text-sm text-gray-600">
                    @if($vac > 0)
                        <span class="text-green-600">Proyek diperkirakan akan lebih hemat dari anggaran</span>
                    @elseif($vac == 0)
                        <span class="text-blue-600">Proyek diperkirakan sesuai anggaran</span>
                    @else
                        <span class="text-red-600">Proyek diperkirakan melebihi anggaran</span>
                    @endif
                </li>
            </ul>
        </x-card>

        <div class="mt-6">
            <x-button href="{{ route('eva.index') }}" label="🔁 Hitung Ulang EVA" />
        </div>
    </div>
</x-layouts.app>
