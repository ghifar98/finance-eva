<x-layouts.app>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Tambah WBS</h2>

        <form id="wbsForm" method="POST">
            @csrf

            <div class="mb-4">
                <label for="project_id" class="block font-medium mb-1">Nama Proyek</label>
                <select name="project_id" id="project_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
                @error('project_id')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div id="wbsItemsContainer">
                <div class="wbs-item mb-6 border rounded p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="mb-3">
                            <label class="block font-medium mb-1">Minggu</label>
                            <input type="text" name="items[0][minggu]" class="w-full border px-3 py-2 rounded" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium mb-1">Kode</label>
                            <input type="text" name="items[0][kode]" class="w-full border px-3 py-2 rounded" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium mb-1">Rencana Persentase Progres (%)</label>
                        <input type="number" name="items[0][rencana_progres]" class="w-full border px-3 py-2 rounded" min="0" max="100" step="0.01" placeholder="Contoh: 3.98" required>
                        <small class="text-gray-500">Masukkan nilai antara 0-100 (contoh: 3.98, 15.25, 50.00)</small>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium mb-1">Deskripsi Pekerjaan</label>
                        <div class="deskripsi-list">
                            <input type="text" name="items[0][deskripsi][]" class="w-full border px-3 py-2 rounded mb-2" required>
                        </div>
                        <button type="button" class="add-deskripsi bg-green-500 text-white px-2 py-1 rounded text-sm">+ Tambah Deskripsi</button>
                    </div>
                </div>
            </div>

            <button type="button" id="addWbsItem" class="bg-yellow-500 text-white px-3 py-1 rounded mb-4">+ Tambah Minggu & Kode Baru</button>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>

    <script>
        let itemIndex = 1;

        document.getElementById('addWbsItem').addEventListener('click', () => {
            const container = document.getElementById('wbsItemsContainer');
            const html = `
                <div class="wbs-item mb-6 border rounded p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="mb-3">
                            <label class="block font-medium mb-1">Minggu</label>
                            <input type="text" name="items[${itemIndex}][minggu]" class="w-full border px-3 py-2 rounded" required>
                        </div>

                        <div class="mb-3">
                            <label class="block font-medium mb-1">Kode</label>
                            <input type="text" name="items[${itemIndex}][kode]" class="w-full border px-3 py-2 rounded" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium mb-1">Rencana Persentase Progres (%)</label>
                        <input type="number" name="items[${itemIndex}][rencana_progres]" class="w-full border px-3 py-2 rounded" min="0" max="100" step="0.01" placeholder="Contoh: 3.98" required>
                        <small class="text-gray-500">Masukkan nilai antara 0-100 (contoh: 3.98, 15.25, 50.00)</small>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium mb-1">Deskripsi Pekerjaan</label>
                        <div class="deskripsi-list">
                            <input type="text" name="items[${itemIndex}][deskripsi][]" class="w-full border px-3 py-2 rounded mb-2" required>
                        </div>
                        <button type="button" class="add-deskripsi bg-green-500 text-white px-2 py-1 rounded text-sm">+ Tambah Deskripsi</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-deskripsi')) {
                const list = e.target.previousElementSibling;
                const indexMatch = list.querySelector('input').name.match(/items\[(\d+)\]/);
                if (indexMatch) {
                    const index = indexMatch[1];
                    list.insertAdjacentHTML('beforeend', `
                        <input type="text" name="items[${index}][deskripsi][]" class="w-full border px-3 py-2 rounded mb-2" required>
                    `);
                }
            }
        });

        // Ubah action URL ke /projects/{project_id}/wbs
        const form = document.getElementById('wbsForm');
        form.addEventListener('submit', function (e) {
            const projectId = document.getElementById('project_id').value;
            if (!projectId) {
                alert("Silakan pilih proyek terlebih dahulu.");
                e.preventDefault();
                return;
            }
            form.action = `/projects/${projectId}/wbs`;
        });
    </script>
</x-layouts.app>