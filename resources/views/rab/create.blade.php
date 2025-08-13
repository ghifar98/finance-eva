<x-layouts.app>
    <div class="max-w-5xl mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-6">Tambah RAB</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('rab.store') }}" method="POST">
            @csrf

            <!-- Pilih Proyek -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Pilih Proyek</label>
                <select name="project_id" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Table Input RAB -->
            <table class="w-full border mt-4" id="rab-table">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-2">Deskripsi</th>
                        <th class="p-2">Unit</th>
                        <th class="p-2">Qty</th>
                        <th class="p-2">Material Supply</th>
                        <th class="p-2">Harga Satuan</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="desc[]" class="w-full border p-2" required></td>
                        <td><input type="text" name="unit[]" class="w-full border p-2"></td>
                        <td><input type="number" name="qty[]" step="0.01" class="w-full border p-2" required></td>
                        <td><input type="text" name="mat_supply[]" class="w-full border p-2"></td>
                        <td><input type="number" name="unit_price[]" step="0.01" class="w-full border p-2" required></td>
                        <td class="text-center">
                            <button type="button" onclick="removeRow(this)" class="text-red-500">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="my-4">
                <button type="button" onclick="addRow()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Baris</button>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Simpan RAB</button>
            </div>
        </form>
    </div>

    <script>
        function addRow() {
            const row = `
                <tr>
                    <td><input type="text" name="desc[]" class="w-full border p-2" required></td>
                    <td><input type="text" name="unit[]" class="w-full border p-2"></td>
                    <td><input type="number" name="qty[]" step="0.01" class="w-full border p-2" required></td>
                    <td><input type="text" name="mat_supply[]" class="w-full border p-2"></td>
                    <td><input type="number" name="unit_price[]" step="0.01" class="w-full border p-2" required></td>
                    <td class="text-center">
                        <button type="button" onclick="removeRow(this)" class="text-red-500">Hapus</button>
                    </td>
                </tr>
            `;
            document.querySelector('#rab-table tbody').insertAdjacentHTML('beforeend', row);
        }

        function removeRow(btn) {
            const row = btn.closest('tr');
            row.remove();
        }
    </script>
</x-layouts.app>
