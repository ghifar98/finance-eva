<x-layouts.app :title="__('Create Sub Account')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        
        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Sub Account Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Isi form di bawah untuk membuat sub-akun baru.</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('account.sub-account.store',['id'=>$account->id]) }}" class="flex flex-col gap-6">
                @csrf
                <x-errors />

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                 
                    {{-- Input Nama Sub Akun --}}
                    <x-input
                        name="name"
                        label="Nama Sub Akun"
                        placeholder="e.g., Kas Kantor"
                        value="{{ old('name') }}"
                        corner-hint="Wajib diisi"
                    />
                </div>

                {{-- Footer --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button label="Batal" flat href="{{ route('account.index') }}" />
                    <x-button label="Simpan Sub Akun" type="submit" primary />
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
