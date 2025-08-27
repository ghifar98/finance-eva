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

        <!-- Kesimpulan EVA -->
        <x-card>
            <h3 class="text-lg font-bold mb-4">📊 Kesimpulan Analisis EVA</h3>
            
            @php
                $overallPerformance = '';
                $performanceClass = '';
                $performanceIcon = '';
                $recommendation = '';
                
                // Menentukan performa keseluruhan berdasarkan CPI dan SPI
                if($cpi >= 1 && $spi >= 1) {
                    $overallPerformance = 'Excellent';
                    $performanceClass = 'text-green-600';
                    $performanceIcon = '🎉';
                    $recommendation = 'Proyek berjalan dengan sangat baik! Kinerja biaya dan jadwal berada pada atau di atas target. Tim dapat melanjutkan dengan strategi saat ini sambil mempertahankan momentum yang ada.';
                } elseif($cpi >= 0.9 && $spi >= 0.9) {
                    $overallPerformance = 'Good';
                    $performanceClass = 'text-blue-600';
                    $performanceIcon = '👍';
                    $recommendation = 'Proyek dalam kondisi yang cukup baik dengan sedikit penyimpangan dari rencana. Diperlukan monitoring ketat dan penyesuaian kecil untuk memastikan proyek tetap pada jalur yang benar.';
                } elseif($cpi >= 0.8 || $spi >= 0.8) {
                    $overallPerformance = 'Warning';
                    $performanceClass = 'text-yellow-600';
                    $performanceIcon = '⚠️';
                    $recommendation = 'Proyek menunjukkan tanda-tanda peringatan dengan penyimpangan yang cukup signifikan. Diperlukan tindakan korektif segera untuk mencegah masalah yang lebih besar.';
                } else {
                    $overallPerformance = 'Critical';
                    $performanceClass = 'text-red-600';
                    $performanceIcon = '🚨';
                    $recommendation = 'Proyek dalam kondisi kritis dengan kinerja yang sangat buruk. Diperlukan evaluasi menyeluruh dan tindakan darurat untuk menyelamatkan proyek dari kegagalan total.';
                }
            @endphp

            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <span class="text-2xl mr-2">{{ $performanceIcon }}</span>
                    <h4 class="text-md font-semibold {{ $performanceClass }}">
                        Status Proyek: {{ $overallPerformance }}
                    </h4>
                </div>
                
                <p class="text-gray-700 mb-4">{{ $recommendation }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="space-y-2">
                        <h5 class="font-medium text-gray-800">Indikator Kunci:</h5>
                        <ul class="space-y-1">
                            <li class="flex justify-between">
                                <span>Efisiensi Biaya (CPI):</span>
                                <span class="{{ $cpi >= 1 ? 'text-green-600' : ($cpi >= 0.9 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($cpi, 2) }}
                                </span>
                            </li>
                            <li class="flex justify-between">
                                <span>Efisiensi Jadwal (SPI):</span>
                                <span class="{{ $spi >= 1 ? 'text-green-600' : ($spi >= 0.9 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($spi, 2) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="space-y-2">
                        <h5 class="font-medium text-gray-800">Proyeksi Akhir:</h5>
                        <ul class="space-y-1">
                            <li class="flex justify-between">
                                <span>Total Biaya Est.:</span>
                                <span>Rp {{ number_format($eac, 0, ',', '.') }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Selisih Anggaran:</span>
                                <span class="{{ $vac >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($vac, 0, ',', '.') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>

        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <x-button href="{{ route('eva.index') }}" label="🔁 Hitung Ulang EVA" />
            
            <button 
                id="continueWorkBtn"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
                onclick="continueWork()"
            >
                <span class="mr-2">▶️</span>
                <span id="btnText">Lanjutkan Pekerjaan</span>
            </button>
        </div>
    </div>

    <script>
        function continueWork() {
            const btn = document.getElementById('continueWorkBtn');
            const btnText = document.getElementById('btnText');
            
            // Ubah tampilan tombol untuk menunjukkan status berubah
            btn.disabled = true;
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-blue-600', 'cursor-not-allowed');
            btnText.textContent = 'Pekerjaan Dilanjutkan';
            
            // Tampilkan notifikasi sukses
            showNotification('✅ Status proyek berhasil diubah menjadi "Pekerjaan Dilanjutkan"', 'success');
            
            // Optional: Redirect setelah delay
            setTimeout(() => {
                // window.location.href = '{{ route("eva.index") }}'; // Uncomment jika ingin redirect
            }, 2000);
        }
        
        function showNotification(message, type = 'info') {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 
                type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 
                'bg-blue-100 border border-blue-400 text-blue-700'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-1">
                        ${message}
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-xl leading-none">&times;</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animasi masuk
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove setelah 5 detik
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }
    </script>
</x-layouts.app>