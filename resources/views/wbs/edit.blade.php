<x-layouts.app>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit WBS</h1>

    <form action="{{ route('wbs.update', $wbs->id) }}" method="POST" class="space-y-4">
      @csrf @method('PUT')
      <div>
        <label class="block font-semibold">Minggu</label>
        <input type="text" name="minggu" value="{{ $wbs->minggu }}" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block font-semibold">Kode</label>
        <input type="text" name="kode" value="{{ $wbs->kode }}" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block font-semibold">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border px-3 py-2 rounded" required>{{ $wbs->deskripsi }}</textarea>
      </div>
      <button class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
    </form>
  </div>
</x-layouts.app>
