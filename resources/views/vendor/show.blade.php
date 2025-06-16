<x-layouts.app :title="__('Vendor Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- View Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Vendor: {{ $vendor->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">A detailed view of the vendor information.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-x-2">
                    <x-button label="Back" href="{{ route('vendor.index') }}" />
                    {{-- <x-button label="Edit" primary href="{{ route('vendor.edit', $vendor->id) }}" /> --}}
                </div>
            </div>

            {{-- Details Grid --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">

                {{-- Helper for displaying details --}}
                @php
                    function DetailItem($label, $value) {
                        return <<<HTML
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">$label</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{$value}</dd>
                        </div>
                        HTML;
                    }
                @endphp

                {!! DetailItem('Name', $vendor->name ?: '-') !!}
                {!! DetailItem('Attention (Attn)', $vendor->attn ?: '-') !!}
                {!! DetailItem('Email', $vendor->email ?: '-') !!}
                {!! DetailItem('GSM No', $vendor->gsm_no ?: '-') !!}
                {!! DetailItem('Quote Reference', $vendor->quote_ref ?: '-') !!}
                {!! DetailItem('Subject', $vendor->subject ?: '-') !!}

                {{-- Address (Full Width) --}}
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $vendor->address ?: 'No address provided.' }}
                    </dd>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
