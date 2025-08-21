<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <form action="{{ route('master-projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <x-card>
                {{-- Tampilkan error validasi --}}
                <x-errors class="mb-4" /> 

                {{-- Grid container --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    {{-- Kode Project --}}
                    <div>
                        <x-input
                            name="kode_project"
                            label="Kode Project :"
                            placeholder="e.g., PRJ-001"
                        />
                    </div>

                    {{-- Nama Projek --}}
                    <div>
                        <x-input
                            name="project_name"
                            label="Nama Projek :"
                            placeholder="e.g., Pengembangan Aplikasi Internal"
                        />
                    </div>

                    {{-- Vendor --}}
                    <div>
                        <x-input
                            name="vendor"
                            label="Vendor :"
                            placeholder="e.g., PT. Solusi Digital Indonesia"
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
                        />
                    </div>

                    {{-- No Kontrak --}}
                    <div>
                        <x-input
                            name="kontrak"
                            label="No Kontrak :"
                            placeholder="e.g., KONTRAK-001"
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
                            oninput="formatRupiah(this)"
                        >
                    </div>

                    {{-- Start Project --}}
                    <div>
                        <x-input
                            type="date"
                            name="start_project"
                            label="Start Project"
                        />
                    </div>

                    {{-- End Project --}}
                    <div>
                        <x-input
                            type="date"
                            name="end_project"
                            label="End Project"
                        />
                    </div>

                    {{-- Asal Kode --}}
                    <div>
                        <x-input
                            name="asal_kode"
                            label="Asal Kode :"
                            placeholder="e.g., GitHub, Local"
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
                    </div>

                </div> {{-- End of grid container --}}

                {{-- Submit Button --}}
                <div class="mt-6 flex justify-end">
                    <x-button type="submit" primary label="Submit Data" />
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
    </script>
</x-layouts.app>
