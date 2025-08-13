<x-layouts.app>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Tambah RAB Perminggu</h1>

        <form method="POST" action="{{ route('rab-weekly.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">Pilih Proyek</label>
                <select name="project_id" class="w-full border px-4 py-2 rounded">
                   
                      @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="weeks-container">
                <!-- Minggu pertama -->
                <div class="week-group border p-4 rounded mb-6">
                    <label class="block font-semibold mb-2">Minggu</label>
                    <input type="text" name="minggu[]" class="minggu-input w-full border px-4 py-2 mb-4" placeholder="Contoh: Minggu 1">

                    <div class="kategori-jumlah-container">
                        <div class="flex gap-4 mb-2">
                            <input type="text" name="kategori[0][]" class="w-full border px-4 py-2" placeholder="Kategori">
                            <input type="number" name="jumlah[0][]" class="w-full border px-4 py-2" placeholder="Jumlah (Rp)">
                        </div>
                    </div>

                    <button type="button" class="add-kategori-jumlah bg-blue-500 text-white px-3 py-1 text-sm rounded">
                        + Tambah Kategori/Jumlah
                    </button>
                </div>
            </div>

            <!-- Tombol tambah minggu -->
            <button type="button" id="add-week" class="bg-indigo-500 text-white px-4 py-2 rounded mb-4">
                + Tambah Minggu Baru
            </button>

            <!-- Tombol Submit -->
            <div class="mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        let weekIndex = 1;

        // Tambah minggu baru
        document.getElementById('add-week').addEventListener('click', () => {
            const container = document.getElementById('weeks-container');

            const html = `
                <div class="week-group border p-4 rounded mb-6">
                    <label class="block font-semibold mb-2">Minggu</label>
                    <input type="text" name="minggu[]" class="minggu-input w-full border px-4 py-2 mb-4" placeholder="Contoh: Minggu ${weekIndex + 1}">

                    <div class="kategori-jumlah-container">
                        <div class="flex gap-4 mb-2">
                            <input type="text" name="kategori[${weekIndex}][]" class="w-full border px-4 py-2" placeholder="Kategori">
                            <input type="number" name="jumlah[${weekIndex}][]" class="w-full border px-4 py-2" placeholder="Jumlah (Rp)">
                        </div>
                    </div>

                    <button type="button" class="add-kategori-jumlah bg-blue-500 text-white px-3 py-1 text-sm rounded">
                        + Tambah Kategori/Jumlah
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            weekIndex++;
        });

        // Tambah kategori/jumlah per minggu
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('add-kategori-jumlah')) {
                const weekGroup = e.target.closest('.week-group');
                const mingguInputs = document.querySelectorAll('.minggu-input');
                const currentIndex = Array.from(document.querySelectorAll('.week-group')).indexOf(weekGroup);

                const container = weekGroup.querySelector('.kategori-jumlah-container');
                const field = `
                    <div class="flex gap-4 mb-2">
                        <input type="text" name="kategori[${currentIndex}][]" class="w-full border px-4 py-2" placeholder="Kategori">
                        <input type="number" name="jumlah[${currentIndex}][]" class="w-full border px-4 py-2" placeholder="Jumlah (Rp)">
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', field);
            }
        });
    </script>
</x-layouts.app>
