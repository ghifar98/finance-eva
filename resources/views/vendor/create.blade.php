<x-layouts.app :title="__('Create Vendor')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Vendor Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Fill in the form below to create a new vendor.</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('vendor.store') }}" class="flex flex-col gap-6">
                @csrf
                <x-errors />
                {{-- Grid Layout for Inputs --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <x-input
                        name="name"
                        label="Vendor Name"
                        placeholder="e.g., PT Mitra Jaya"
                        corner-hint="Required"
                        value="{{ old('name') }}"
                    />

                    <x-input
                        name="attn"
                        label="Attention (Attn)"
                        placeholder="e.g., John Doe"
                        value="{{ old('attn') }}"
                    />

                    <x-input
                        name="email"
                        label="Email"
                        placeholder="e.g., example@vendor.com"
                        value="{{ old('email') }}"
                    />

                    <x-input
                        name="gsm_no"
                        label="GSM No"
                        placeholder="e.g., 081234567890"
                        value="{{ old('gsm_no') }}"
                    />

                    <x-input
                        name="quote_ref"
                        label="Quote Reference"
                        placeholder="e.g., QO-2025-001"
                        value="{{ old('quote_ref') }}"
                    />

                    <x-input
                        name="subject"
                        label="Subject"
                        placeholder="e.g., Material Supply Quotation"
                        value="{{ old('subject') }}"
                    />

                    {{-- Address (Full Width) --}}
                    <div class="col-span-1 md:col-span-2">
                        <x-textarea
                            name="address"
                            label="Address"
                            placeholder="Enter vendor address"
                        >{{ old('address') }}</x-textarea>
                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button
                        label="Cancel"
                        flat
                        href="{{ route('vendor.index') }}"
                    />
                    <x-button
                        label="Save Vendor"
                        type="submit"
                        primary
                    />
                </div>
    
            </form>

        </div>
    </div>
    
</x-layouts.app>
