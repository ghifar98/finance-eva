<x-layouts.app :title="__('Account Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- View Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Account: {{ $account->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">A detailed view of the account information.</p>
                </div>
                {{-- Action Buttons --}}
                <div class="flex gap-x-2">
                    <x-button label="Back" href="{{ route('account.index') }}" />
                    <x-button label="Edit" primary href="{{ route('account.edit', $account->id) }}" />
                </div>
            </div>

            {{-- Details Grid --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">

                {{-- Helper component for displaying data --}}
                @php
                    function DetailItem($label, $value, $isCurrency = false) {
                        $formattedValue = $isCurrency ? 'Rp ' . number_format($value, 2, ',', '.') : ($value ?: '-');
                        return <<<HTML
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">$label</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">$formattedValue</dd>
                        </div>
                        HTML;
                    }
                @endphp

                {!! DetailItem('Account Code', $account->code) !!}
                {!! DetailItem('Account Name', $account->name) !!}
                {!! DetailItem('Report Position', $account->pos_laporan) !!}
                {!! DetailItem('Normal Balance Position', $account->pos_saldo) !!}
                {!! DetailItem('Opening Debit', $account->debit, true) !!}
                {!! DetailItem('Opening Credit', $account->credit, true) !!}

                {{-- Description (Full Width) --}}
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $account->deskripsi ?: 'No description provided.' }}
                    </dd>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
