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

        <!-- Project Selection Form -->
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
            <!-- Project Info (if filtered) -->
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
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Minggu ke-{{ $eva->week_number }} - {{ $eva->report_date }}
                                </h2>
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
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('eva.show', $eva->id) }}" class="px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                            </div>
                        </div>

                        <!-- Basic Metrics -->
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

                        <!-- Variance Metrics -->
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Variance (SV)</p>
                                <p class="text-lg font-semibold {{ $eva->sv >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($eva->sv, 0, ',', '.') }}
                                    <span class="text-sm ml-1">({{ $eva->pv != 0 ? number_format(($eva->sv / $eva->pv) * 100, 2) : 0 }}%)</span>
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Variance (CV)</p>
                                <p class="text-lg font-semibold {{ $eva->cv >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($eva->cv, 0, ',', '.') }}
                                    <span class="text-sm ml-1">({{ $eva->ac != 0 ? number_format(($eva->cv / $eva->ac) * 100, 2) : 0 }}%)</span>
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Performance Index (SPI)</p>
                                <p class="text-lg font-semibold {{ $eva->spi >= 1 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($eva->spi, 2) }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Performance Index (CPI)</p>
                                <p class="text-lg font-semibold {{ $eva->cpi >= 1 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($eva->cpi, 2) }}
                                </p>
                            </div>
                        </div>

                        <!-- Forecast Metrics -->
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Estimate at Completion (EAC)</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($eva->eac, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Variance at Completion (VAC)</p>
                                <p class="text-lg font-semibold {{ $eva->vac >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($eva->vac, 0, ',', '.') }}
                                    <span class="text-sm ml-1">({{ $eva->bac != 0 ? number_format(($eva->vac / $eva->bac) * 100, 2) : 0 }}%)</span>
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Estimate to Complete (ETC)</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    Rp {{ number_format(max($eva->eac - $eva->ac, 0), 0, ',', '.' ) }}
                                </p>
                            </div>
                        </div>

                        <!-- Notes Section with Edit Toggle -->
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-medium text-gray-700">Catatan</h4>
                                @if(!empty($eva->notes))
                                    <button onclick="toggleEdit({{ $eva->id }})" id="edit-btn-{{ $eva->id }}" class="text-sm text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                @endif
                            </div>

                            <!-- Display Notes (Read Mode) -->
                            <div id="notes-display-{{ $eva->id }}" class="{{ empty($eva->notes) ? 'hidden' : '' }}">
                                <div class="bg-gray-50 border border-gray-200 rounded-md p-3 min-h-[60px]">
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $eva->notes ?: 'Belum ada catatan.' }}</p>
                                </div>
                            </div>

                            <!-- Edit Form (Edit Mode) -->
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
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleEdit(evaId) {
            const displayDiv = document.getElementById(`notes-display-${evaId}`);
            const formDiv = document.getElementById(`notes-form-${evaId}`);
            const editBtn = document.getElementById(`edit-btn-${evaId}`);
            
            // Hide display, show form
            displayDiv.classList.add('hidden');
            formDiv.classList.remove('hidden');
            
            // Focus on textarea
            document.getElementById(`notes-input-${evaId}`).focus();
            
            // Hide edit button
            editBtn.classList.add('hidden');
        }

        function cancelEdit(evaId) {
            const displayDiv = document.getElementById(`notes-display-${evaId}`);
            const formDiv = document.getElementById(`notes-form-${evaId}`);
            const editBtn = document.getElementById(`edit-btn-${evaId}`);
            const textarea = document.getElementById(`notes-input-${evaId}`);
            
            // Reset textarea value to original
            const originalNotes = displayDiv.querySelector('p').textContent;
            textarea.value = originalNotes === 'Belum ada catatan.' ? '' : originalNotes;
            
            // Show display, hide form
            displayDiv.classList.remove('hidden');
            formDiv.classList.add('hidden');
            
            // Show edit button
            editBtn.classList.remove('hidden');
        }

        // Auto-hide form after successful submission (if using session flash)
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                // Find the EVA that was just updated and switch to display mode
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
            });
        @endif
    </script>
</x-layouts.app>