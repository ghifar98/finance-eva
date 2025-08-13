<x-layouts.app>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Data RAB Perminggu</h1>
            <a href="{{ route('rab-weekly.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + Tambah RAB
            </a>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Pilih Proyek --}}
        <form action="{{ route('rab-weekly.index') }}" method="GET" class="mb-6">
            <label for="project_id" class="block mb-2 font-semibold text-gray-700">Pilih Proyek:</label>
            <select name="project_id" id="project_id" onchange="this.form.submit()" class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded">
                <option value="">-- Pilih Proyek --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->project_name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Data RAB --}}
        @if($data->isNotEmpty())
            @foreach($data as $projectName => $items)
                <div class="mb-4 border rounded shadow">
                    <button class="w-full text-left px-4 py-3 bg-gray-100 font-semibold text-lg toggle-btn">
                        {{ $projectName }}
                    </button>

                    <div class="toggle-content hidden px-4 py-3 bg-white border-t">
                        <table class="table-auto w-full text-left border border-collapse">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border px-4 py-2">Minggu</th>
                                    <th class="border px-4 py-2">Kategori</th>
                                    <th class="border px-4 py-2">Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $mingguGroups = $items->groupBy('minggu');
                                @endphp

                                @foreach($mingguGroups as $minggu => $groupItems)
                                    @foreach($groupItems as $index => $item)
                                        <tr>
                                            <td class="border px-4 py-2">
                                                {{ $index === 0 ? $minggu : '' }}
                                            </td>
                                            <td class="border px-4 py-2">{{ $item->kategori }}</td>
                                            <td class="border px-4 py-2">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="border px-4 py-2 font-bold text-right" colspan="2">Total Minggu {{ $minggu }}</td>
                                        <td class="border px-4 py-2 font-bold">
                                            Rp {{ number_format($groupItems->sum('jumlah'), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @elseif(request()->has('project_id'))
            <div class="text-gray-500">Tidak ada data RAB untuk proyek ini.</div>
        @endif
    </div>

    <script>
        document.querySelectorAll('.toggle-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                content.classList.toggle('hidden');
            });
        });
    </script>
</x-layouts.app>
