{{-- resources/views/purchase/index.blade.php --}}
<x-layouts.app :title="__('Purchase Management')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-orange-50">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Title Section -->
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                                Purchase Management
                            </h1>
                            <p class="text-slate-600 mt-1">Manage your purchase orders efficiently and elegantly</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Quick Stats -->
                        <div class="flex items-center gap-4 px-4 py-2 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-700">{{ \App\Models\Purchase::count() }}</div>
                                <div class="text-xs text-blue-600">Total Orders</div>
                            </div>
                            <div class="w-px h-8 bg-blue-300"></div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-700">{{ \App\Models\Purchase::whereMonth('created_at', now()->month)->count() }}</div>
                                <div class="text-xs text-green-600">This Month</div>
                            </div>
                        </div>
                        
                        <!-- New Purchase Button -->
                        <a href="{{ route('purchase.create') }}" 
                           class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-200 focus:ring-4 focus:ring-orange-300">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Purchase
                            <div class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-200"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Bar -->
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-slate-700">Quick Actions:</span>
                    
                

        <!-- Main Table Container -->
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
            <livewire:purchase-table />
        </div>

        <!-- Additional Info Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <div class="flex items-center mb-4">
                 
</x-layouts.app>