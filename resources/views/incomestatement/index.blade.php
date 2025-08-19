<x-layouts.app :title="__('Income Statement')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-orange-50 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Title Section -->
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                                Income Statement
                            </h1>
                            <p class="text-slate-600 mt-1">Financial overview of your business performance</p>
                        </div>
                    </div>
                    
                    <!-- Filter Section -->
                    <div class="flex-1 max-w-3xl">
                     <form action="{{ route('incomestatement.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Start Date</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                value="{{ request('start_date') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-2">End Date</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                value="{{ request('end_date') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Project</label>
                            <select 
                                name="project_id" 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-span-1 flex items-end gap-2">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Apply
                            </button>
                            <a href="{{ route('incomestatement.index') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-center transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Table Section -->
            <div class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
                <livewire:incomestatement-table
                    :startDate="$startDate ?? ''"
                    :endDate="$endDate ?? ''"
                    :projectId="$projectIdSelected ?? ''"
                />
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Summary -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 text-center">
                    <div class="text-sm text-slate-600 mb-2">Total Amount</div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ 'Rp ' . number_format($amount, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Breakdown Cards -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 text-center">
                    <div class="text-sm text-slate-600 mb-2">Gross Profit</div>
                    <div class="text-xl font-bold text-green-600">
                        {{ $calculatedTotals['gross_profit'] ?? 'Rp 0' }}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 text-center">
                    <div class="text-sm text-slate-600 mb-2">Operating Income</div>
                    <div class="text-xl font-bold text-blue-600">
                        {{ $calculatedTotals['operating_income'] ?? 'Rp 0' }}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 text-center">
                    <div class="text-sm text-slate-600 mb-2">Net Income</div>
                    <div class="text-xl font-bold text-purple-600">
                        {{ $calculatedTotals['other_income_balance'] ?? 'Rp 0' }}
                    </div>
                </div>
            </div>

            <!-- Grouped Account Summary -->
            @if (!empty($grouped))
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="divide-y divide-slate-200">
                        @foreach ($grouped as $code => $account)
                            @php
                                $highlightClasses = 'bg-slate-50';
                                $textClasses = 'font-medium';
                                // Highlight important accounts
                                if (in_array(substr($code, 0, 2), ['42', '50'])) {
                                    $highlightClasses = 'bg-blue-50';
                                    $textClasses = 'font-bold text-blue-800';
                                }
                            @endphp
                            <div class="flex justify-between items-center px-6 py-4 hover:bg-slate-50 transition-colors duration-150 {{ $highlightClasses }}">
                                <div class="flex items-center">
                                    <div class="w-10 text-sm text-slate-500">{{ $code }}</div>
                                    <div class="ml-4 {{ $textClasses }}">{{ $account['name'] }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-semibold">
                                        Rp {{ number_format($account['total'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 text-center">
                    <div class="text-slate-400 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500">No income statement data found for the selected period.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>    