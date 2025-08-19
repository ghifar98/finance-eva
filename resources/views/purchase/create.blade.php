<x-layouts.app :title="__('Create Purchase')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Purchase Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Fill in the form below to create a new purchase order.</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('purchase.store') }}" class="flex flex-col gap-6">
                @csrf
                <x-errors />
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <x-input
                        name="date"
                        label="Date"
                        type="date"
                        value="{{ old('date') }}"
                        corner-hint="Required"
                    />

                    <x-input
                        name="po_no"
                        label="PO Number"
                        placeholder="e.g., PO-2025-001"
                        value="{{ old('po_no') }}"
                        disabled
                    />

                                    <x-input
                        name="company"
                        label="Company"
                        value="PT. INDERA SAE PRATAMA"
                        readonly
                    />
{{-- Ganti x-select project dengan ini --}}
<div>
    <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Project <span class="text-red-500">*</span>
    </label>
    <select name="project_id" id="project_id" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <option value="">Select project</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                {{ $project->project_name }}
            </option>
        @endforeach
    </select>
</div>

{{-- Ganti x-select vendor dengan ini --}}
<div>
    <label for="vendor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Vendor <span class="text-red-500">*</span>
    </label>
    <select name="vendor_id" id="vendor_id" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <option value="">Select vendor</option>
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                {{ $vendor->name }}
            </option>
        @endforeach
    </select>
</div>

                                        <x-input
                        name="package"
                        label="Package"
                        value="CIVIL & STEEL STR WORK"
                        readonly
                    />

                                <x-input
                    name="rep_name"
                    label="Representative Name"
                    value="ASEP DINAR W.ST.MT"
                    readonly
                />

                                <x-input
                    name="phone"
                    label="Phone"
                    value="0882567881"
                    readonly
                />
                  
                </div>

                {{-- Actions --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button
                        label="Cancel"
                        flat
                        href="{{ route('purchase.index') }}"
                    />
                    <x-button
                        label="Save Purchase"
                        type="submit"
                        primary
                    />
                </div>
            </form>

        </div>
    </div>
</x-layouts.app>
