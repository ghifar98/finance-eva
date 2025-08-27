<x-layouts.app>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit WBS</h1>

    <form action="{{ route('wbs.update', $wbs->id) }}" method="POST" class="space-y-4">
      @csrf 
      @method('PUT')
      
      <div>
        <label class="block font-semibold">Project ID</label>
        <select name="project_id" class="w-full border px-3 py-2 rounded" required>
          @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ $wbs->project_id == $project->id ? 'selected' : '' }}>
              {{ $project->project_name }}
            </option>
          @endforeach
        </select>
        @error('project_id')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>
      
      <div>
        <label class="block font-semibold">Minggu</label>
        <input type="text" name="minggu" value="{{ old('minggu', $wbs->minggu) }}" class="w-full border px-3 py-2 rounded" required>
        @error('minggu')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>
      
      <div>
        <label class="block font-semibold">Kode</label>
        <input type="text" name="kode" value="{{ old('kode', $wbs->kode) }}" class="w-full border px-3 py-2 rounded" required>
        @error('kode')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>
      
      <div>
        <label class="block font-semibold">Rencana Persentase Progres (%)</label>
        <input type="number" 
               name="rencana_progres" 
               value="{{ old('rencana_progres', $wbs->rencana_progres) }}" 
               class="w-full border px-3 py-2 rounded" 
               min="0" 
               max="100" 
               step="0.01" 
               placeholder="Contoh: 3.98" 
               required>
        <small class="text-gray-500">Masukkan nilai antara 0-100 (contoh: 3.98, 15.25, 50.00)</small>
        @error('rencana_progres')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
        
        <!-- Debug info - remove this after testing -->
        <div class="text-xs text-gray-400 mt-1">
          Current value in DB: {{ $wbs->rencana_progres ?? 'NULL' }}
        </div>
      </div>
      
      <div>
        <label class="block font-semibold">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border px-3 py-2 rounded" rows="4" required>{{ old('deskripsi', $wbs->deskripsi) }}</textarea>
        @error('deskripsi')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="flex gap-2">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        <a href="{{ route('wbs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
      </div>
    </form>
  </div>
</x-layouts.app>