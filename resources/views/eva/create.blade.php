<x-layouts.app :title="__('Hitung Evaluasi')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Hitung EVA</h1>

        {{-- Form Pilih Proyek --}}
        <form action="{{ route('eva.create') }}" method="GET" class="mb-6">
            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Proyek</label>
            <select name="project_id" id="project_id" class="w-full p-2 border rounded" onchange="this.form.submit()">
                <option value="">-- Pilih Proyek --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                        {{ $project->project_name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </form>

        @if($selectedProjectId)
            {{-- Form Hitung EVA --}}
            <form action="{{ route('eva.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $selectedProjectId }}">

                {{-- Pilih Minggu --}}
                <div class="mb-4">
                    <label for="minggu_ke" class="block text-sm font-medium text-gray-700 mb-1">Minggu ke</label>
                    <select name="minggu_ke" id="minggu_ke" class="w-full p-2 border rounded" required>
                        <option value="">-- Pilih Minggu --</option>
                        @foreach($weeklyProgress as $weekNumber => $progressGroup)
                            @php
                                $firstDate = \Carbon\Carbon::parse($progressGroup->first()->progress_date)->format('d M Y');
                            @endphp
                            <option value="{{ $weekNumber }}" {{ $selectedWeek == $weekNumber ? 'selected' : '' }}>
                                Minggu {{ $weekNumber }} ({{ $firstDate }})
                            </option>
                        @endforeach
                    </select>
                    @error('minggu_ke')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- PV Otomatis --}}
                @if($selectedWeek && $plannedValue !== null)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Planned Value (PV) Minggu ke-{{ $selectedWeek }}
                        </label>
                        <div class="p-2 bg-gray-100 border rounded">
                            Rp {{ number_format($plannedValue, 0, ',', '.') }}
                        </div>
                        <input type="hidden" name="pv" value="{{ $plannedValue }}">
                    </div>
                @endif

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full p-2 border rounded"></textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Simpan dan Hitung EVA
                </button>
            </form>
        @endif
    </div>

    {{-- Script Otomatis Reload saat Pilih Minggu --}}
    <script>
        document.getElementById('minggu_ke')?.addEventListener('change', function () {
            const projectId = document.querySelector('[name="project_id"]').value;
            const weekNumber = this.value;
            if (projectId && weekNumber) {
                const url = `{{ route('eva.create') }}?project_id=${projectId}&minggu_ke=${weekNumber}`;
                window.location.href = url;
            }
        });
    </script>
</x-layouts.app>
