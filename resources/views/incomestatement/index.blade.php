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
                    <div class="flex-1 max-w-4xl">
                        <form action="{{ route('incomestatement.index') }}" method="GET" class="space-y-4">
                            <!-- First Row: Date Inputs and Project Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                 <div class="col-span-1">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Project</label>
                                    <select 
                                        name="project_id" 
                                        id="project_select"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="updateProjectDates()"
                                    >
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option 
                                                value="{{ $project->id }}" 
                                                data-start-date="{{ $project->start_project ? \Carbon\Carbon::parse($project->start_project)->format('d M Y') : 'Not set' }}" 
                                                data-end-date="{{ $project->end_project ? \Carbon\Carbon::parse($project->end_project)->format('d M Y') : 'Not set' }}"
                                                {{ request('project_id') == $project->id ? 'selected' : '' }}
                                            >
                                                {{ $project->project_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
                                
                               
                                
                                <div class="col-span-1 flex items-end gap-2">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        Apply
                                    </button>
                                    <a href="{{ route('incomestatement.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-center transition-colors">
                                        Reset
                                    </a>
                                </div>
                            </div>

                            <!-- Second Row: Project Dates Display -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="project-dates-container" 
                                 style="{{ $selectedProject ? 'display: grid;' : 'display: none;' }}">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">Project Start Date</span>
                                    </div>
                                    <div class="mt-1 text-blue-900 font-semibold" id="project-start-date">
                                        {{ $selectedProject ? ($selectedProject->start_project ? \Carbon\Carbon::parse($selectedProject->start_project)->format('d M Y') : 'Not set') : '' }}
                                    </div>
                                </div>

                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-700">Project End Date</span>
                                    </div>
                                    <div class="mt-1 text-green-900 font-semibold" id="project-end-date">
                                        {{ $selectedProject ? ($selectedProject->end_project ? \Carbon\Carbon::parse($selectedProject->end_project)->format('d M Y') : 'Not set') : '' }}
                                    </div>
                                </div>
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

    <script>
        function updateProjectDates() {
            const select = document.getElementById('project_select');
            const container = document.getElementById('project-dates-container');
            const startDateElement = document.getElementById('project-start-date');
            const endDateElement = document.getElementById('project-end-date');
            
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const startDate = selectedOption.getAttribute('data-start-date');
                const endDate = selectedOption.getAttribute('data-end-date');
                
                startDateElement.textContent = startDate;
                endDateElement.textContent = endDate;
                container.style.display = 'grid';
            } else {
                container.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateProjectDates();
        });
    </script>
</x-layouts.app>