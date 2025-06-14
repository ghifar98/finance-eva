<x-layouts.app :title="__('Edit Account')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Edit Account Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Update the form below to modify the account.</p>
            </div>

            {{-- The form points to the update route and uses the PUT method --}}
            <form method="POST" action="{{ route('account.update', $account->id) }}" class="flex flex-col gap-6">
                @csrf
                @method('PUT') {{-- Method spoofing for update --}}

                {{-- Grid Layout for Inputs --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Input for 'code' --}}
                    <x-input
                        name="code"
                        label="Account Code"
                        placeholder="e.g., 1101"
                        corner-hint="Required"
                        value="{{ old('code', $account->code) }}"
                    />

                    {{-- Input for 'name' --}}
                    <x-input
                        name="name"
                        label="Account Name"
                        placeholder="e.g., Kas Besar"
                        corner-hint="Required"
                        value="{{ old('name', $account->name) }}"
                    />

                    {{-- Select for 'pos_laporan' (Report Position) --}}
                    <x-select
                        label="Report Position"
                        placeholder="Select a report position"
                        name="pos_laporan"
                        :options="['Neraca', 'Laba Rugi']"
                        :value="old('pos_laporan', $account->pos_laporan)"
                        corner-hint="Required"
                    />

                    {{-- Select for 'pos_saldo' (Balance Position) --}}
                    <x-select
                        label="Normal Balance Position"
                        placeholder="Select a balance position"
                        name="pos_saldo"
                        :options="['Debit', 'Kredit']"
                        :value="old('pos_saldo', $account->pos_saldo)"
                        corner-hint="Required"
                    />

                    {{-- Currency Input for 'debit' --}}
                    <div class="col-span-1">
                        <x-currency
                            label="Opening Debit"
                            placeholder="0.00"
                            name="debit"
                            thousands="."
                            decimal=","
                            precision="2"
                            prefix="Rp "
                            value="{{ old('debit', $account->debit) }}"
                        />
                    </div>

                    {{-- Currency Input for 'credit' --}}
                    <div class="col-span-1">
                        <x-currency
                            label="Opening Credit"
                            placeholder="0.00"
                            name="credit"
                            thousands="."
                            decimal=","
                            precision="2"
                            prefix="Rp "
                            value="{{ old('credit', $account->credit) }}"
                        />
                    </div>

                    {{-- Textarea for 'deskripsi' (Full Width) --}}
                    <div class="col-span-1 md:col-span-2">
                        <x-textarea
                            name="deskripsi"
                            label="Description"
                            placeholder="Enter a brief description for the account (optional)"
                        >{{ old('deskripsi', $account->deskripsi) }}</x-textarea>
                    </div>
                </div>

                {{-- Form Actions/Footer --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button
                        label="Cancel"
                        flat
                        href="{{ route('account.index') }}"
                    />
                    <x-button
                        label="Update Account"
                        type="submit"
                        primary
                    />
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
