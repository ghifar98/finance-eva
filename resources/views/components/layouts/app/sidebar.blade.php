<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    @include('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Add these meta tags for better navigation --}}
    <meta name="turbo-cache-control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Custom styles for notifications and modals */
        #notification-banner {
            position: fixed;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            z-index: 1000;
            transition: bottom 0.5s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        #notification-banner.show {
            bottom: 20px;
        }

        #confirmation-modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #confirmation-modal-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        #confirmation-modal {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 400px;
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        #confirmation-modal-overlay.show #confirmation-modal {
            transform: scale(1);
        }
    </style>
</head>
<body class="min-h-screen bg-white">

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>

{{-- Loading Indicator --}}
<div id="loadingIndicator" class="fixed top-0 left-0 w-full h-1 bg-blue-500 transform scale-x-0 origin-left transition-transform duration-300 z-[9999]"></div>

<!-- Custom Notification Banner -->
<div id="notification-banner"></div>

<!-- Custom Confirmation Modal -->
<div id="confirmation-modal-overlay">
    <div id="confirmation-modal" class="text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-2" id="modal-title">Are you sure?</h3>
        <p class="text-gray-600 mb-6" id="modal-message">This action cannot be undone.</p>
        <div class="flex justify-center gap-4">
            <button id="modal-cancel-btn" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
            <button id="modal-confirm-btn" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Confirm</button>
        </div>
    </div>
</div>


<div class="flex min-h-screen">
    <!-- Desktop Sidebar -->
    <div class="sidebar flex flex-col">
        <div class="logo-container">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 rtl:space-x-reverse navigation-link">
                <x-app-logo />
            </a>
            <button class="toggle-btn" id="sidebarToggle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 expand-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>   
        </div>

        <div class="flex flex-col flex-grow overflow-y-auto px-1 py-4">
            <!-- Dashboard -->
            @if (Auth::user()?->isRole('project_director') || Auth::user()?->isRole('project_manager'))
            <a href="{{ route('dashboard') }}" 
               class="nav-item navigation-link {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}" 
               data-route="dashboard">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                </div>
                <span class="nav-text">{{ __('Dashboard') }}</span>
            </a>
            @endif

            <!-- Master Project -->
            @if (Auth::user()?->isRole('project_director') || Auth::user()?->isRole('project_manager'))
            <a href="{{ route('master-projects.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('master-projects.*') ? 'active-nav' : '' }}" 
               data-route="master-projects">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>
                </div>
                <span class="nav-text">{{ __('Master Project') }}</span>
            </a>
            @endif
            
            <!-- WBS -->
            @if (Auth::user()?->isRole('project_manager'))
            <a href="{{ route('wbs.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('wbs.*') ? 'active-nav' : '' }}" 
               data-route="wbs">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                </div>
                <span class="nav-text">{{ __('WBS') }}</span>
            </a>
            @endif
            
            <!-- RAB Proyek -->
            @if (Auth::user()?->isRole('project_manager'))
            <a href="{{ route('rab.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('rab.*') ? 'active-nav' : '' }}" 
               data-route="rab">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
                <span class="nav-text">{{ __('RAB Proyek') }}</span>
            </a>
            @endif

            <!-- RAB Perminggu -->
            @if (Auth::user()?->isRole('project_manager'))
            <a href="{{ route('rab-weekly.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('rab-weekly.*') ? 'active-nav' : '' }}" 
               data-route="rab-weekly">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                </div>
                <span class="nav-text">{{ __('RAB Perminggu') }}</span>
            </a>
            @endif

            <!-- Account -->
            @if (Auth::user()?->isRole('finance_officer'))
            <a href="{{ route('account.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('account.*') ? 'active-nav' : '' }}" 
               data-route="account">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="nav-text">{{ __('Account') }}</span>
            </a>
            @endif
            
            <!-- Vendor -->
            @if (Auth::user()?->isRole('staff'))
            <a href="{{ route('vendor.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('vendor.*') ? 'active-nav' : '' }}" 
               data-route="vendor">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                </div>
                <span class="nav-text">{{ __('Vendor') }}</span>
            </a>
            @endif
            
            <!-- Purchase -->
            @if (Auth::user()?->isRole('finance_officer') || Auth::user()?->isRole('staff'))
            <a href="{{ route('purchase.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('purchase.*') ? 'active-nav' : '' }}" 
               data-route="purchase">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                </div>
                <span class="nav-text">{{ __('Purchase') }}</span>
            </a>
            @endif
            
            <!-- General Ledger -->
            @if (Auth::user()?->isRole('finance_officer'))
            <a href="{{ route('generalleadger.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('generalleadger.*') ? 'active-nav' : '' }}" 
               data-route="generalleadger">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
                <span class="nav-text">{{ __('General Ledger') }}</span>
            </a>
            @endif
            
            <!-- Income Statement -->
            @if (Auth::user()?->isRole('project_director') || Auth::user()?->isRole('finance_officer'))
            <a href="{{ route('incomestatement.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('incomestatement.*') ? 'active-nav' : '' }}" 
               data-route="incomestatement">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                </div>
                <span class="nav-text">{{ __('Income Statement') }}</span>
            </a>
            @endif
            
            <!-- EVA -->
            @if (Auth::user()?->isRole('project_director') || Auth::user()?->isRole('project_manager'))
            <a href="{{ route('eva.index') }}" 
               class="nav-item navigation-link {{ request()->routeIs('eva.*') ? 'active-nav' : '' }}" 
               data-route="eva">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                </div>
                <span class="nav-text">{{ __('EVA') }}</span>
            </a>
            @endif

            <!-- Role Indicator -->
            @if (config('app.debug') || Auth::user()?->isRole('project_director'))
            <div class="px-3 py-2 mt-4 text-xs text-gray-500 border-t border-gray-200">
                <span class="nav-text">Current Role: {{ Auth::user()?->role ?? 'Unknown' }}</span>
            </div>
            @endif
        </div>

        <!-- User Menu -->
        <div class="user-menu">
            <div class="relative">
                <div class="user-info" id="userMenuToggle">
                    <div class="user-avatar">
                        {{ auth()->user()?->initials() ?? '??' }}
                    </div>
                    <div class="user-name">{{ auth()->user()?->name ?? 'Guest' }}</div>
                    <div class="user-email">{{ auth()->user()?->email ?? '-' }}</div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="color: rgba(255, 255, 255, 0.5); transition: all 0.3s ease; margin-left: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                
                <div class="dropdown-menu hidden" id="userDropdownMenu">
                    <a href="{{ route('settings.profile') }}" class="dropdown-item navigation-link">
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        {{ __('Settings') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full" id="logout-form">
                        @csrf
                        <button type="button" id="logout-button" class="dropdown-item w-full text-left">
                            <div class="dropdown-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                            </div>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header lg:hidden">
        <a href="{{ route('dashboard') }}" class="navigation-link">
            <x-app-logo class="mobile-logo" />
        </a>
        <button class="mobile-menu-btn" id="mobileMenuToggle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        {{ $slot }}
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- ELEMENT SELECTORS ---
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const userMenuToggle = document.getElementById('userMenuToggle');
    const userDropdown = document.getElementById('userDropdownMenu');
    const mobileMenuBtn = document.getElementById('mobileMenuToggle');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const logoutButton = document.getElementById('logout-button');
    const logoutForm = document.getElementById('logout-form');

    // --- NOTIFICATION & MODAL ELEMENTS ---
    const notificationBanner = document.getElementById('notification-banner');
    const modalOverlay = document.getElementById('confirmation-modal-overlay');
    const modalConfirmBtn = document.getElementById('modal-confirm-btn');
    const modalCancelBtn = document.getElementById('modal-cancel-btn');
    let confirmationCallback = null;

    // --- HELPER FUNCTIONS ---
    
    /**
     * Shows a notification banner with a message.
     * @param {string} message The message to display.
     * @param {number} duration Time in ms to show the banner.
     */
    function showNotification(message, duration = 3000) {
        if (!notificationBanner) return;
        notificationBanner.textContent = message;
        notificationBanner.classList.add('show');
        setTimeout(() => {
            notificationBanner.classList.remove('show');
        }, duration);
    }

    /**
     * Shows a confirmation modal.
     * @param {object} options - Configuration for the modal.
     * @param {string} options.title - The modal title.
     * @param {string} options.message - The modal message.
     * @param {function} onConfirm - The callback function to execute on confirmation.
     */
    function showConfirmation({ title, message, onConfirm }) {
        if (!modalOverlay) return;
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        confirmationCallback = onConfirm;
        modalOverlay.classList.add('show');
    }

    /**
     * Hides the confirmation modal.
     */
    function hideConfirmation() {
        if (!modalOverlay) return;
        modalOverlay.classList.remove('show');
        confirmationCallback = null;
    }

    // --- INITIALIZATION ---

    // Setup CSRF token for Axios if it exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken && typeof window.axios !== 'undefined') {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }

    // --- EVENT LISTENERS ---

    // Sidebar toggle functionality
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('sidebar-collapsed'));
        });
        // Restore sidebar state from localStorage
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('sidebar-collapsed');
        }
    }

    // User dropdown functionality
    if (userMenuToggle && userDropdown) {
        userMenuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!userMenuToggle.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }

    // Mobile menu functionality
    if (mobileMenuBtn && mobileOverlay && sidebar) {
        const closeMobileMenu = () => {
            sidebar.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.classList.remove('mobile-menu-open');
        };

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.classList.toggle('mobile-menu-open');
        });

        mobileOverlay.addEventListener('click', closeMobileMenu);
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });
    }

    // Livewire navigation events for loading indicator
    if (loadingIndicator && typeof Livewire !== 'undefined') {
        const startLoading = () => loadingIndicator.style.transform = 'scaleX(1)';
        const stopLoading = () => loadingIndicator.style.transform = 'scaleX(0)';

        document.addEventListener('livewire:navigating', startLoading);
        document.addEventListener('livewire:navigated', () => {
            stopLoading();
            // Close mobile menu after navigation
            if (sidebar?.classList.contains('active')) {
                mobileMenuBtn.click();
            }
        });
        document.addEventListener('livewire:navigate-error', (event) => {
            stopLoading();
            // Fallback to regular navigation on error
            if (event.detail?.url) {
                window.location.href = event.detail.url;
            }
        });
    }

    // General navigation click handler (using Livewire's wire:navigate is preferred)
    document.addEventListener('click', (e) => {
        const navLink = e.target.closest('.navigation-link');
        // Let Livewire handle navigation if `wire:navigate` is present
        // This handler is a fallback or for links without `wire:navigate`
        if (navLink && !navLink.hasAttribute('wire:navigate')) {
            e.preventDefault();
            const href = navLink.getAttribute('href');
            if (href && href !== '#') {
                if (typeof Livewire !== 'undefined' && Livewire.navigate) {
                    Livewire.navigate(href);
                } else {
                    window.location.href = href;
                }
            }
        }
    });

    // Logout button functionality
    if (logoutButton && logoutForm) {
        logoutButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            showConfirmation({
                title: 'Confirm Logout',
                message: 'Are you sure you want to log out?',
                onConfirm: () => {
                    logoutForm.submit();
                }
            });
        });
    }

    // Modal confirmation/cancellation
    if (modalConfirmBtn && modalCancelBtn) {
        modalConfirmBtn.addEventListener('click', () => {
            if (typeof confirmationCallback === 'function') {
                confirmationCallback();
            }
            hideConfirmation();
        });
        modalCancelBtn.addEventListener('click', hideConfirmation);
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                hideConfirmation();
            }
        });
    }

    // Disable buttons on form submission (excluding logout form)
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form.id === 'logout-form') return;

        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(btn => {
            btn.disabled = true;
            const originalText = btn.tagName === 'INPUT' ? btn.value : btn.innerHTML;
            if (btn.tagName === 'INPUT') {
                btn.value = 'Loading...';
            } else {
                btn.innerHTML = 'Loading...';
            }
            // Re-enable after a timeout to prevent permanently disabled buttons on validation failure
            setTimeout(() => {
                btn.disabled = false;
                if (btn.tagName === 'INPUT') {
                    btn.value = originalText;
                } else {
                    btn.innerHTML = originalText;
                }
            }, 5000); // 5-second timeout
        });
    });

    // Network status monitoring
    window.addEventListener('online', () => showNotification('Connection restored. You are back online.', 3000));
    window.addEventListener('offline', () => showNotification('Connection lost. Some features may not work.', 5000));
});
</script>
</body>
</html>
