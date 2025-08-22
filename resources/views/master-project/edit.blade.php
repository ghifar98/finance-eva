<x-layouts.app :title="__('Edit Project')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <form action="{{ route('master-projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <x-card>
                {{-- Tampilkan error validasi --}}
                <x-errors class="mb-4" /> 

                {{-- Header Edit --}}
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Project: {{ $project->project_name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">Ubah hanya bagian yang diperlukan, field lainnya akan tetap menggunakan data sebelumnya</p>
                </div>

                {{-- Grid container --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Kode Project --}}
                    <div>
                        <x-input
                            name="kode_project"
                            label="Kode Project :"
                            placeholder="e.g., PRJ-001"
                            value="{{ old('kode_project', $project->kode_project) }}"
                        />
                    </div>

                    {{-- Nama Projek --}}
                    <div>
                        <x-input
                            name="project_name"
                            label="Nama Projek :"
                            placeholder="e.g., Pengembangan Aplikasi Internal"
                            value="{{ old('project_name', $project->project_name) }}"
                        />
                    </div>

                    {{-- Vendor --}}
                    <div>
                        <x-input
                            name="vendor"
                            label="Vendor :"
                            placeholder="e.g., PT. Solusi Digital Indonesia"
                            value="{{ old('vendor', $project->vendor) }}"
                        />
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <x-input
                            name="tahun"
                            label="Tahun :"
                            placeholder="e.g., 2025"
                            type="number"
                            min="1900"
                            max="{{ date('Y') + 5 }}"
                            value="{{ old('tahun', $project->tahun) }}"
                        />
                    </div>

                    {{-- No Kontrak --}}
                    <div>
                        <x-input
                            name="kontrak"
                            label="No Kontrak :"
                            placeholder="e.g., KONTRAK-001"
                            value="{{ old('kontrak', $project->kontrak) }}"
                        />
                    </div>

                    {{-- Nilai (Currency) --}}
                    <div>
                        <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai</label>
                        <input 
                            type="text" 
                            id="nilai" 
                            name="nilai" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Rp. 1.000.000"
                            value="{{ old('nilai', $project->nilai ? 'Rp. ' . number_format($project->nilai, 0, ',', '.') : '') }}"
                            oninput="formatRupiah(this)"
                        >
                        @if($project->nilai)
                            <p class="text-xs text-gray-500 mt-1">Nilai saat ini: Rp. {{ number_format($project->nilai, 0, ',', '.') }}</p>
                        @endif
                    </div>

                    {{-- Start Project --}}
                    <div>
                        <x-input
                            type="date"
                            name="start_project"
                            label="Start Project"
                            value="{{ old('start_project', $project->start_project ? \Carbon\Carbon::parse($project->start_project)->format('Y-m-d') : '') }}"
                        />
                        @if($project->start_project)
                            <p class="text-xs text-gray-500 mt-1">Tanggal mulai saat ini: {{ \Carbon\Carbon::parse($project->start_project)->format('d/m/Y') }}</p>
                        @endif
                    </div>

                    {{-- End Project --}}
                    <div>
                        <x-input
                            type="date"
                            name="end_project"
                            label="End Project"
                            value="{{ old('end_project', $project->end_project ? \Carbon\Carbon::parse($project->end_project)->format('Y-m-d') : '') }}"
                        />
                        @if($project->end_project)
                            <p class="text-xs text-gray-500 mt-1">Tanggal selesai saat ini: {{ \Carbon\Carbon::parse($project->end_project)->format('d/m/Y') }}</p>
                        @endif
                    </div>

                    {{-- Asal Kode --}}
                    <div>
                        <x-input
                            name="asal_kode"
                            label="Asal Kode :"
                            placeholder="e.g., GitHub, Local"
                            value="{{ old('asal_kode', $project->asal_kode) }}"
                        />
                    </div>

                    {{-- Upload Dokumen --}}
                    <div>
                        <x-input
                            type="file"
                            name="data_proyek"
                            label="Upload Dokumen (PDF/JPG/PNG)"
                            accept=".pdf,.jpg,.jpeg,.png"
                        />
                        @if($project->data_proyek)
                            <div class="mt-2 p-3 bg-blue-50 rounded-md">
                                <p class="text-sm text-blue-800">
                                    <strong>Dokumen saat ini:</strong> 
                                 
                                </p>
                                <p class="text-xs text-blue-600 mt-1">Upload file baru untuk mengganti dokumen yang ada</p>
                            </div>
                        @endif
                    </div>

                </div> {{-- End of grid container --}}

                {{-- Progress Information --}}
                @if($project->progress > 0)
                    <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <h4 class="text-sm font-medium text-green-800">Informasi Progress</h4>
                        <p class="text-sm text-green-700 mt-1">Progress saat ini: {{ $project->progress }}%</p>
                        <p class="text-xs text-green-600 mt-1">Progress akan tetap dipertahankan setelah update</p>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('master-projects.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('master-projects.show', $project->id) }}" 
                           class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-300 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Lihat Detail
                        </a>
                        <x-button type="submit" primary label="Update Data" />
                    </div>
                </div>
            </x-card>
        </form>
    </div>

    {{-- Script Format Rupiah --}}
    <script>
        function formatRupiah(el) {
            let value = el.value.replace(/[^0-9]/g, ""); // hanya angka
            if (value) {
                el.value = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(value).replace("IDR", "Rp.");
            }
        }

        // Format nilai on page load jika ada nilai
        document.addEventListener('DOMContentLoaded', function() {
            const nilaiInput = document.getElementById('nilai');
            if (nilaiInput && nilaiInput.value) {
                // Jika sudah dalam format Rupiah, biarkan saja
                if (!nilaiInput.value.includes('Rp.')) {
                    formatRupiah(nilaiInput);
                }
            }
        });
    </script>
</x-layouts.app>