<x-layouts.app>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-6">
            
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-slate-800">Work Breakdown Structure</h1>
                        <p class="text-slate-600 mt-1">Struktur Rincian Kerja</p>
                    </div>
                    @if(isset($groupedWbs) && count($groupedWbs) > 0)
                        <a href="{{ route('wbs.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Data
                        </a>
                    @endif
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Project Selection --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <form action="{{ route('wbs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="project_id" class="block text-sm font-medium text-slate-700 mb-2">Pilih Proyek:</label>
                        <select name="project_id" id="project_id" 
                                class="w-full border border-slate-300 rounded-lg px-4 py-3 text-slate-800 bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition-colors duration-200">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tampilkan
                    </button>
                </form>
            </div>

            @if(isset($groupedWbs) && count($groupedWbs) > 0)
                <!-- WBS Table -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-slate-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Data Work Breakdown Structure</h3>
                    </div>
                    
                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Minggu</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Kode</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Deskripsi</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php $prevMinggu = null; $prevKode = null; @endphp
                                @foreach($groupedWbs as $key => $items)
                                    @php [$minggu, $kode] = explode('-', $key); @endphp
                                    @foreach($items as $wbs)
                                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                                            <td class="px-6 py-4">
                                                @if($minggu != $prevMinggu)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                        {{ $minggu }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($kode != $prevKode)
                                                    <span class="font-semibold text-slate-800">{{ $kode }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-slate-700">
                                                {{ $wbs->deskripsi }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center gap-2">
                                                    <a href="{{ route('wbs.edit', $wbs->id) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('wbs.destroy', $wbs->id) }}" method="POST" 
                                                          onsubmit="return confirm('Hapus data ini?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @php $prevMinggu = $minggu; $prevKode = $kode; @endphp
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Section -->
                    <div class="bg-slate-800 px-6 py-4">
                        <div class="flex justify-between items-center text-white">
                            <div>
                                <p class="text-sm text-slate-300">Total Item WBS</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-semibold text-orange-400">
                                    {{ collect($groupedWbs)->flatten()->count() }} Item
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(request()->has('project_id'))
                <!-- Empty State with Add Button -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                    <div class="text-slate-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-800 mb-2">Belum ada data WBS</h3>
                    <p class="text-slate-600 mb-6">Tidak ada data Work Breakdown Structure untuk proyek ini.</p>
                    <a href="{{ route('wbs.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Data WBS
                    </a>
                </div>

            @else
                <!-- Initial State -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                    <div class="text-slate-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-800 mb-2">Pilih Proyek</h3>
                    <p class="text-slate-600">Silakan pilih proyek untuk melihat Work Breakdown Structure.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>