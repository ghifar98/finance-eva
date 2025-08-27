<x-layouts.app :title="__('Earned Value Analysis')">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Analisis Nilai Hasil (EVA) Proyek</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau kinerja proyek dengan metrik Earned Value Analysis</p>
            </div>
            <a href="{{ route('eva.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Hitung EVA Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <form method="GET" action="{{ route('eva.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full">
                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Proyek</label>
                    <select name="project_id" id="project_id" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Proyek --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }} ({{ $project->vendor }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Filter
                </button>
            </form>
        </div>

        @if($evas->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">
                    @if(request('project_id'))
                        Tidak ada data EVA untuk proyek ini
                    @else
                        Belum ada data EVA
                    @endif
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('project_id'))
                        Mulai dengan menghitung EVA untuk proyek ini
                    @else
                        Mulai dengan menghitung EVA untuk proyek Anda
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('eva.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition inline-flex items-center">
                        Buat EVA Baru
                    </a>
                </div>
            </div>
        @else
            @if(request('project_id') && $selectedProject = $projects->firstWhere('id', request('project_id')))
                <div class="bg-white p-6 rounded-lg shadow-sm border border-blue-100">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $selectedProject->project_name }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Vendor</p>
                            <p class="font-medium">{{ $selectedProject->vendor }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Mulai</p>
                            <p class="font-medium">{{ $selectedProject->start_project }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Selesai</p>
                            <p class="font-medium">{{ $selectedProject->end_project }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                @foreach ($evas as $eva)
                    <div class="border border-gray-200 rounded-lg p-6 bg-white shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        Minggu ke-{{ $eva->week_number }} - {{ $eva->report_date }}
                                    </h2>
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $eva->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($eva->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        @if($eva->status === 'approved')
                                            ✅ Disetujui
                                        @elseif($eva->status === 'rejected')
                                            ❌ Ditolak
                                        @else
                                            ⏳ Menunggu Persetujuan
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $eva->project->project_name ?? 'Proyek Tidak Dikenali' }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eva->progress >= 70 ? 'bg-green-100 text-green-800' : ($eva->progress >= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        Progress: {{ $eva->progress }}%
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eva->spi >= 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        SPI: {{ number_format($eva->spi, 2) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eva->cpi >= 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        CPI: {{ number_format($eva->cpi, 2) }}
                                    </span>
                                </div>
                                
                                <!-- Status Info -->
                                @if($eva->status_updated_at && $eva->statusUpdatedBy)
                                    <div class="mt-2 text-xs text-gray-500">
                                        Status diperbarui oleh <strong>{{ $eva->statusUpdatedBy->name }}</strong> 
                                        pada {{ $eva->status_updated_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- EVA Metrics Cards -->
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Planned Value (PV)</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($eva->pv, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Earned Value (EV)</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($eva->ev, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actual Cost (AC)</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($eva->ac, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Budget at Completion (BAC)</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($eva->bac, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Variance and Index Metrics -->
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Variance (SV)</p>
                                <p class="text-lg font-semibold {{ $eva->sv >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($eva->sv, 0, ',', '.') }}
                                    <span class="text-sm ml-1">({{ $eva->pv != 0 ? number_format(($eva->sv / $eva->pv) * 100, 2) : 0 }}%)</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($eva->sv >= 0)
                                        ✅ Proyek berjalan lebih cepat dari jadwal.
                                    @else
                                        ❌ Proyek mengalami keterlambatan dari jadwal.
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Variance (CV)</p>
                                <p class="text-lg font-semibold {{ $eva->cv >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($eva->cv, 0, ',', '.') }}
                                    <span class="text-sm ml-1">({{ $eva->ac != 0 ? number_format(($eva->cv / $eva->ac) * 100, 2) : 0 }}%)</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($eva->cv >= 0)
                                        ✅ Biaya lebih rendah dari anggaran (hemat).
                                    @else
                                        ❌ Biaya melebihi anggaran (boros).
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Performance Index (SPI)</p>
                                <p class="text-lg font-semibold {{ $eva->spi >= 1 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($eva->spi, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($eva->spi >= 1)
                                        ✅ Kinerja jadwal baik (sesuai/lebih cepat).
                                    @else
                                        ❌ Kinerja jadwal buruk (terlambat).
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Performance Index (CPI)</p>
                                <p class="text-lg font-semibold {{ $eva->cpi >= 1 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($eva->cpi, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($eva->cpi >= 1)
                                        ✅ Kinerja biaya efisien (sesuai/lebih hemat).
                                    @else
                                        ❌ Kinerja biaya tidak efisien (boros).
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Additional Metrics -->
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Estimate at Completion (EAC)</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($eva->eac, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">Prediksi total biaya proyek saat selesai.</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Variance at Completion (VAC)</p>
                                <p class="text-lg font-semibold {{ $eva->vac >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($eva->vac, 0, ',', '.') }}
                                    <span class="text-sm ml-1">({{ $eva->bac != 0 ? number_format(($eva->vac / $eva->bac) * 100, 2) : 0 }}%)</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($eva->vac >= 0)
                                        ✅ Proyek diprediksi selesai di bawah anggaran.
                                    @else
                                        ❌ Proyek diprediksi akan melebihi anggaran.
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Estimate to Complete (ETC)</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    Rp {{ number_format(max($eva->eac - $eva->ac, 0), 0, ',', '.' ) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Prediksi sisa biaya untuk menyelesaikan proyek.</p>
                            </div>
                        </div>

                        <!-- Kesimpulan EVA -->
                        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-lg border border-blue-200">
                            <h4 class="text-lg font-bold mb-4 flex items-center gap-2">
                                <span class="text-2xl">📊</span>
                                Kesimpulan Analisis EVA Minggu ke-{{ $eva->week_number }}
                            </h4>
                            
                            @php
                                // Hitung total AC kumulatif sampai minggu ini
                                $totalAcKumulatif = DB::table('evas')
                                    ->where('project_id', $eva->project_id)
                                    ->where('week_number', '<=', $eva->week_number)
                                    ->sum('ac');
                                
                                // Hitung sisa budget yang tersedia (BAC - Total AC yang sudah dikeluarkan)
                                $sisaBudget = $eva->bac - $totalAcKumulatif;
                                
                                // Hitung ETC
                                $etc = max($eva->eac - $eva->ac, 0);
                                
                                // Tentukan status berdasarkan kondisi aktual
                                $overBudgetAmount = $eva->eac - $eva->bac; // Jika positif, akan over budget
                                $isOverBudget = $overBudgetAmount > 0;
                                $isOnTrack = $eva->vac >= 0 && $etc <= ($eva->bac * 0.8); // VAC positif dan ETC wajar
                                $isDelayed = $eva->spi < 0.9;
                                $isOverCost = $eva->vac < 0; // VAC negatif menandakan over budget
                                
                                $performanceIcon = '';
                                $performanceClass = '';
                                $kesimpulan = '';
                                
                                if ($isOverBudget && $isDelayed) {
                                    $performanceIcon = '🚨';
                                    $performanceClass = 'text-red-700 bg-red-100';
                                    $kesimpulan = "Tanda-tanda menunjukkan masalah yang besar! Proyek akan mengalami over budget sebesar Rp " . number_format($overBudgetAmount, 0, ',', '.') . " dari anggaran awal (VAC: Rp " . number_format($eva->vac, 0, ',', '.') . "). Proyek juga mengalami keterlambatan jadwal yang signifikan dengan SPI " . number_format($eva->spi, 2) . ". Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . ", sementara estimasi biaya penyelesaian (ETC) adalah Rp " . number_format($etc, 0, ',', '.') . ".";
                                } elseif ($isOverBudget || $eva->vac < 0) {
                                    $performanceIcon = '⚠️';
                                    $performanceClass = 'text-red-700 bg-red-100';
                                    if ($eva->vac < 0) {
                                        $kesimpulan = "Proyeksi menunjukkan proyek akan mengalami over budget. VAC menunjukkan defisit sebesar Rp " . number_format(abs($eva->vac), 0, ',', '.') . " dari anggaran yang ditetapkan (BAC: Rp " . number_format($eva->bac, 0, ',', '.') . "). Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . ", namun estimasi biaya penyelesaian (ETC) mencapai Rp " . number_format($etc, 0, ',', '.') . ". Diperlukan tindakan pengendalian biaya segera.";
                                    } else {
                                        $kesimpulan = "Proyeksi menunjukkan proyek akan mengalami over budget sebesar Rp " . number_format($overBudgetAmount, 0, ',', '.') . " dari anggaran yang ditetapkan (BAC: Rp " . number_format($eva->bac, 0, ',', '.') . "). Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . ", sementara estimasi biaya penyelesaian (ETC) adalah Rp " . number_format($etc, 0, ',', '.') . ". Diperlukan tindakan pengendalian biaya segera.";
                                    }
                                } elseif ($isDelayed && $etc > ($eva->bac * 0.5)) {
                                    $performanceIcon = '⚠️';
                                    $performanceClass = 'text-yellow-700 bg-yellow-100';
                                    $kesimpulan = "Proyek mengalami keterlambatan jadwal (SPI: " . number_format($eva->spi, 2) . ") dengan estimasi biaya penyelesaian (ETC) yang cukup tinggi sebesar Rp " . number_format($etc, 0, ',', '.') . ". VAC saat ini menunjukkan " . ($eva->vac >= 0 ? "surplus sebesar Rp " . number_format($eva->vac, 0, ',', '.') : "defisit sebesar Rp " . number_format(abs($eva->vac), 0, ',', '.')) . ". Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . ". Diperlukan evaluasi strategi pelaksanaan.";
                                } elseif ($isDelayed) {
                                    $performanceIcon = '⏰';
                                    $performanceClass = 'text-yellow-700 bg-yellow-100';
                                    $kesimpulan = "Proyek mengalami keterlambatan jadwal dengan SPI " . number_format($eva->spi, 2) . ", namun kondisi biaya masih terkendali dengan VAC sebesar Rp " . number_format($eva->vac, 0, ',', '.') . ". Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . " dengan estimasi biaya penyelesaian (ETC) sebesar Rp " . number_format($etc, 0, ',', '.') . ". Fokus pada percepatan jadwal dengan tetap mempertahankan efisiensi biaya.";
                                } elseif ($etc > ($eva->bac * 0.6)) {
                                    $performanceIcon = '💰';
                                    $performanceClass = 'text-yellow-700 bg-yellow-100';
                                    $kesimpulan = "Proyek menunjukkan estimasi biaya penyelesaian (ETC) yang tinggi sebesar Rp " . number_format($etc, 0, ',', '.') . ", namun jadwal masih relatif on track. VAC menunjukkan " . ($eva->vac >= 0 ? "surplus sebesar Rp " . number_format($eva->vac, 0, ',', '.') : "defisit sebesar Rp " . number_format(abs($eva->vac), 0, ',', '.')) . ". Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . ". Diperlukan pengendalian biaya yang lebih ketat.";
                                } elseif ($isOnTrack) {
                                    $performanceIcon = '🎉';
                                    $performanceClass = 'text-green-700 bg-green-100';
                                    $kesimpulan = "Proyek berjalan sangat baik! VAC menunjukkan surplus sebesar Rp " . number_format($eva->vac, 0, ',', '.') . " yang menandakan proyek akan selesai di bawah anggaran. Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . " dengan estimasi biaya penyelesaian (ETC) yang efisien sebesar Rp " . number_format($etc, 0, ',', '.') . ". Proyeksi menunjukkan pengelolaan yang sangat baik.";
                                } else {
                                    $performanceIcon = '👍';
                                    $performanceClass = 'text-blue-700 bg-blue-100';
                                    $kesimpulan = "Proyek dalam kondisi yang dapat diterima dengan VAC sebesar Rp " . number_format($eva->vac, 0, ',', '.') . ". Budget yang tersisa untuk melanjutkan project sebesar Rp " . number_format(max($sisaBudget, 0), 0, ',', '.') . " dengan estimasi biaya penyelesaian (ETC) sebesar Rp " . number_format($etc, 0, ',', '.') . ". Monitoring berkelanjutan diperlukan untuk memastikan proyek tetap pada jalur yang benar.";
                                }
                            @endphp

                            <div class="mb-4">
                                <div class="flex items-center mb-3">
                                    <span class="text-3xl mr-3">{{ $performanceIcon }}</span>
                                    <div class="px-4 py-2 rounded-lg {{ $performanceClass }}">
                                        <h5 class="text-lg font-semibold">
                                            Analisis Kinerja Proyek
                                        </h5>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 rounded-md border-l-4 border-blue-400 mb-4">
                                    <p class="text-gray-800 text-sm leading-relaxed">{{ $kesimpulan }}</p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        <h6 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            Ringkasan Finansial
                                        </h6>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Budget Awal (BAC):</span>
                                                <span class="text-sm font-semibold">Rp {{ number_format($eva->bac, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Total Biaya s/d Minggu {{ $eva->week_number }}:</span>
                                                <span class="text-sm font-semibold">Rp {{ number_format($totalAcKumulatif, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Sisa Budget:</span>
                                                <span class="px-2 py-1 rounded text-white text-xs font-medium {{ $sisaBudget >= 0 ? 'bg-green-500' : 'bg-red-500' }}">
                                                    Rp {{ number_format(max($sisaBudget, 0), 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @if($isOverBudget)
                                                <div class="flex justify-between items-center p-2 bg-red-50 rounded border border-red-200">
                                                    <span class="font-medium text-xs text-red-700">Proyeksi Over Budget:</span>
                                                    <span class="px-2 py-1 rounded bg-red-500 text-white text-xs font-medium">
                                                        Rp {{ number_format($overBudgetAmount, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        <h6 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Indikator Performa
                                        </h6>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Progress Fisik:</span>
                                                <span class="px-2 py-1 rounded text-white text-xs font-medium {{ $eva->progress >= 70 ? 'bg-green-500' : ($eva->progress >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}">
                                                    {{ $eva->progress }}%
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Variance at Completion (VAC):</span>
                                                <span class="px-2 py-1 rounded text-white text-xs font-medium {{ $eva->vac >= 0 ? 'bg-green-500' : 'bg-red-500' }}">
                                                    Rp {{ number_format($eva->vac, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Estimate to Complete (ETC):</span>
                                                <span class="px-2 py-1 rounded text-white text-xs font-medium {{ $etc <= ($eva->bac * 0.5) ? 'bg-green-500' : ($etc <= ($eva->bac * 0.7) ? 'bg-yellow-500' : 'bg-red-500') }}">
                                                    Rp {{ number_format($etc, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-xs">Estimasi Total Biaya (EAC):</span>
                                                <span class="text-sm font-semibold">Rp {{ number_format($eva->eac, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes and Status Management Section -->
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-medium text-gray-700">Catatan & Status</h4>
                                <div class="flex gap-2">
                                    <!-- Status Update Buttons (hanya untuk project director) -->
                                    @if(Auth::user()?->isRole("project_director"))
                                        <div class="flex gap-1">
                                            @if($eva->status !== 'approved')
                                                <button onclick="updateStatus({{ $eva->id }}, 'approved')" class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui
                                                </button>
                                            @endif
                                            @if($eva->status !== 'rejected')
                                                <button onclick="showRejectModal({{ $eva->id }})" class="text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Tolak
                                                </button>
                                            @endif
                                            @if($eva->status !== 'pending')
                                                <button onclick="updateStatus({{ $eva->id }}, 'pending')" class="text-xs px-2 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Pending
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                    <!-- Edit Notes Button -->
                                    @if(Auth::user()?->isRole("project_director"))
                                    @if(!empty($eva->notes))
                                        <button onclick="toggleEdit({{ $eva->id }})" id="edit-btn-{{ $eva->id }}" class="text-sm text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                    @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Notes Display -->
                            <div id="notes-display-{{ $eva->id }}" class="{{ empty($eva->notes) ? 'hidden' : '' }}">
                                <div class="bg-gray-50 border border-gray-200 rounded-md p-3 min-h-[60px]">
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $eva->notes ?: 'Belum ada catatan.' }}</p>
                                </div>
                            </div>

                            <!-- Notes Form -->
                            <form action="{{ route('eva.updateNotes', $eva->id) }}" method="POST" id="notes-form-{{ $eva->id }}" class="{{ !empty($eva->notes) ? 'hidden' : '' }}">
                                @csrf
                                <div class="flex flex-col gap-3">
                                    <textarea 
                                        name="notes" 
                                        rows="3" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                        placeholder="Tambahkan catatan untuk EVA ini..."
                                        id="notes-input-{{ $eva->id }}"
                                    >{{ old('notes', $eva->notes) }}</textarea>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Simpan
                                        </button>
                                        @if(!empty($eva->notes))
                                            <button type="button" onclick="cancelEdit({{ $eva->id }})" class="px-3 py-1.5 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition text-sm flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Batal
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Continue Work Button Section -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 mb-1">Tindakan Lanjutan</h5>
                                    <p class="text-xs text-gray-500">Berdasarkan analisis EVA, tentukan langkah selanjutnya untuk proyek ini.</p>
                                </div>
                                
                                <button 
                                    id="continueWorkBtn-{{ $eva->id }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200 shadow-sm"
                                    onclick="continueWork({{ $eva->id }})"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="btnText-{{ $eva->id }}">Lanjutkan Pekerjaan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal untuk Penolakan -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak EVA</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea 
                        name="reason" 
                        id="reason" 
                        rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm" 
                        placeholder="Jelaskan alasan penolakan EVA ini..."
                        required
                    ></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Tolak EVA
                    </button>
                </div>
                <input type="hidden" name="status" value="rejected">
            </form>
        </div>
    </div>

   <script>
// Existing functions
function toggleEdit(evaId) {
    const displayDiv = document.getElementById(`notes-display-${evaId}`);
    const formDiv = document.getElementById(`notes-form-${evaId}`);
    const editBtn = document.getElementById(`edit-btn-${evaId}`);
    
    displayDiv.classList.add('hidden');
    formDiv.classList.remove('hidden');
    document.getElementById(`notes-input-${evaId}`).focus();
    editBtn.classList.add('hidden');
}

function cancelEdit(evaId) {
    const displayDiv = document.getElementById(`notes-display-${evaId}`);
    const formDiv = document.getElementById(`notes-form-${evaId}`);
    const editBtn = document.getElementById(`edit-btn-${evaId}`);
    const textarea = document.getElementById(`notes-input-${evaId}`);
    
    const originalNotes = displayDiv.querySelector('p').textContent;
    textarea.value = originalNotes === 'Belum ada catatan.' ? '' : originalNotes;
    
    displayDiv.classList.remove('hidden');
    formDiv.classList.add('hidden');
    editBtn.classList.remove('hidden');
}

// New functions for status management
function updateStatus(evaId, status) {
    if (confirm(`Apakah Anda yakin ingin mengubah status EVA ini menjadi ${getStatusText(status)}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/eva/${evaId}/status`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="status" value="${status}">
        `;
        
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal(evaId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    
    form.action = `/eva/${evaId}/status`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Focus on reason textarea
    document.getElementById('reason').focus();
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Clear the form
    document.getElementById('reason').value = '';
}

function getStatusText(status) {
    switch(status) {
        case 'approved': return 'disetujui';
        case 'rejected': return 'ditolak';
        case 'pending': return 'menunggu persetujuan';
        default: return 'tidak diketahui';
    }
}

// === NEW ENHANCED CONTINUE WORK FUNCTIONS ===

// Fungsi untuk menyimpan status di localStorage
function saveWorkStatus(evaId, status) {
    const workStatuses = JSON.parse(localStorage.getItem('evaWorkStatuses') || '{}');
    workStatuses[evaId] = {
        status: status,
        timestamp: new Date().toISOString(),
        user: '{{ Auth::user()->name ?? "Unknown" }}'
    };
    localStorage.setItem('evaWorkStatuses', JSON.stringify(workStatuses));
}

// Fungsi untuk mengambil status dari localStorage
function getWorkStatus(evaId) {
    const workStatuses = JSON.parse(localStorage.getItem('evaWorkStatuses') || '{}');
    return workStatuses[evaId] || null;
}

// Enhanced Continue Work Function
function continueWork(evaId) {
    const btn = document.getElementById(`continueWorkBtn-${evaId}`);
    const btnText = document.getElementById(`btnText-${evaId}`);
    
    // Cek apakah sudah pernah diklik sebelumnya
    const existingStatus = getWorkStatus(evaId);
    if (existingStatus && existingStatus.status === 'continued') {
        return;
    }
    
    // Konfirmasi dari user
    if (!confirm('Apakah Anda yakin ingin melanjutkan pekerjaan untuk EVA ini?')) {
        return;
    }
    
    // Disable tombol dan ubah tampilan
    btn.disabled = true;
    btn.classList.remove('bg-green-600', 'hover:bg-green-700');
    btn.classList.add('bg-blue-600', 'cursor-not-allowed');
    btnText.textContent = 'Pekerjaan Dilanjutkan';
    
    // Ubah icon menjadi checkmark
    const icon = btn.querySelector('svg');
    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />`;
    
    // Simpan status ke localStorage
    saveWorkStatus(evaId, 'continued');
    
    // Tampilkan notifikasi
    showNotification('Status proyek berhasil diubah menjadi "Pekerjaan Dilanjutkan"', 'success');
    
    // Tambahkan informasi waktu
    addWorkContinuedInfo(evaId);
}

// Fungsi untuk menambahkan informasi waktu
function addWorkContinuedInfo(evaId) {
    const btn = document.getElementById(`continueWorkBtn-${evaId}`);
    const workStatus = getWorkStatus(evaId);
    
    if (!workStatus) return;
    
    // Hapus info lama jika ada
    const existingInfo = document.getElementById(`workInfo-${evaId}`);
    if (existingInfo) {
        existingInfo.remove();
    }
    
    // Buat elemen info baru
    const infoDiv = document.createElement('div');
    infoDiv.id = `workInfo-${evaId}`;
    infoDiv.className = 'mt-2 text-xs text-gray-500';
    
    const date = new Date(workStatus.timestamp);
    const formattedDate = date.toLocaleString('id-ID');
    
    infoDiv.innerHTML = `
        <div class="flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Pekerjaan dilanjutkan pada ${formattedDate}</span>
        </div>
    `;
    
    btn.parentNode.appendChild(infoDiv);
}

// Fungsi untuk inisialisasi status tombol
function initializeContinueWorkButtons() {
    document.querySelectorAll('[id^="continueWorkBtn-"]').forEach(btn => {
        const evaId = btn.id.split('-')[1];
        const workStatus = getWorkStatus(evaId);
        
        if (workStatus && workStatus.status === 'continued') {
            const btnText = document.getElementById(`btnText-${evaId}`);
            
            btn.disabled = true;
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-blue-600', 'cursor-not-allowed');
            btnText.textContent = 'Pekerjaan Dilanjutkan';
            
            // Ubah icon
            const icon = btn.querySelector('svg');
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />`;
            
            // Tambahkan info waktu
            addWorkContinuedInfo(evaId);
        }
    });
}

// Enhanced notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-1 text-sm font-medium">
                ${message}
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-lg leading-none hover:opacity-75 font-bold">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeContinueWorkButtons();
    
    @if(session('success'))
        const forms = document.querySelectorAll('[id^="notes-form-"]');
        forms.forEach(form => {
            const evaId = form.id.split('-')[2];
            const displayDiv = document.getElementById(`notes-display-${evaId}`);
            const editBtn = document.getElementById(`edit-btn-${evaId}`);
            
            if (displayDiv && editBtn) {
                form.classList.add('hidden');
                displayDiv.classList.remove('hidden');
                editBtn.classList.remove('hidden');
            }
        });
    @endif
});

// Prevent clicking disabled buttons
document.addEventListener('click', function(e) {
    if (e.target.disabled && e.target.id && e.target.id.startsWith('continueWorkBtn-')) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
});
</script>

    <!-- Add CSRF token meta tag if not already present -->
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
</x-layouts.app>