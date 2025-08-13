<x-layouts.app>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-6">
            
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-slate-800">Daftar RAB</h1>
                        <p class="text-slate-600 mt-1">Rencana Anggaran Biaya</p>
                    </div>
                    <a href="{{ route('rab.create') }}" class=" hover:bg-slate-900 text-black px-6 py-2.5 rounded-lg font-medium transition-colors duration-200">
                        + Tambah RAB
                    </a>
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
            @elseif(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Project Selection --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <form action="{{ route('rab.index') }}" method="GET">
                    <label for="project_id" class="block text-sm font-medium text-slate-700 mb-2">Pilih Proyek:</label>
                    <select name="project_id" id="project_id" onchange="this.form.submit()" 
                            class="w-full border border-slate-300 rounded-lg px-4 py-3 text-slate-800 bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-100 transition-colors duration-200">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($selectedProject)
                <!-- Project Info -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                    <div class="border-l-4 border-orange-500 pl-4">
                        <h2 class="text-xl font-semibold text-slate-800 mb-2">{{ $selectedProject->project_name }}</h2>
                        <p class="text-slate-600">{{ $selectedProject->project_description }}</p>
                    </div>
                </div>

                @if($rabs->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                        <div class="text-slate-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-800 mb-2">Belum ada data RAB</h3>
                        <p class="text-slate-600">Belum ada data RAB untuk proyek ini.</p>
                    </div>
                @else
                    <!-- RAB Table -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-slate-800 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Detail RAB</h3>
                        </div>
                        
                        <!-- Table Content -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-100 border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Deskripsi</th>
                                        <th class="px-6 py-4 text-center text-sm font-medium text-slate-700">Satuan</th>
                                        <th class="px-6 py-4 text-center text-sm font-medium text-slate-700">Qty</th>
                                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-700">Harga Satuan</th>
                                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-700">Total + PPN</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($rabs as $rab)
                                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                                            <td class="px-6 py-4 text-slate-800 font-medium">{{ $rab->desc }}</td>
                                            <td class="px-6 py-4 text-center text-slate-600">{{ $rab->unit }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    {{ $rab->qty }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-slate-800 font-medium">
                                                Rp{{ number_format($rab->unit_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-orange-600 font-semibold">
                                                Rp{{ number_format($rab->total_after_tax, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Total Section -->
                        <div class="bg-slate-800 px-6 py-6">
                            <div class="flex justify-between items-center">
                                <div class="text-white">
                                    <p class="text-sm text-slate-300">Total Keseluruhan RAB</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ count($rabs) }} item tercatat</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-orange-400">
                                        Rp{{ number_format($total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                    <div class="text-slate-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-800 mb-2">Pilih Proyek</h3>
                    <p class="text-slate-600">Silakan pilih proyek untuk melihat RAB.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>