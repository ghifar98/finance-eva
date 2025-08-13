<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    @include('partials.head')
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        /* Luxury Sidebar Styles */
        .sidebar {
            width: 300px;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: linear-gradient(180deg, #1a2332 0%, #0f1419 50%, #0a0e13 100%);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        /* Luxury gradient overlay */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.08) 0%, rgba(255, 179, 71, 0.04) 100%);
            pointer-events: none;
        }
        
        .sidebar-collapsed {
            width: 85px;
        }
        
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .group-heading,
        .sidebar-collapsed .user-details {
            opacity: 0;
            transform: translateX(-20px);
            pointer-events: none;
        }
        
        .sidebar-collapsed .expand-icon {
            transform: rotate(180deg);
        }
        
        /* Navigation Items */
        .active-nav {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.2) 0%, rgba(255, 179, 71, 0.15) 100%) !important;
            border-left: 4px solid #ff8a4c !important;
            color: #ff8a4c !important;
            font-weight: 600;
            box-shadow: 
                0 8px 25px -8px rgba(255, 138, 76, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        
        .active-nav .nav-icon {
            color: #ff8a4c !important;
            transform: scale(1.1);
        }
        
        .group-heading {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 1.5rem 2rem 0.75rem;
            margin-top: 2rem;
            color: rgba(255, 179, 71, 0.9);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            position: relative;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .group-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 2rem;
            right: 2rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 179, 71, 0.3), transparent);
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            margin: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9375rem;
            font-weight: 500;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .nav-item:hover::before {
            opacity: 1;
        }
        
        .nav-item:hover {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.15) 0%, rgba(255, 179, 71, 0.1) 100%);
            color: #ffb347;
            transform: translateX(8px);
            box-shadow: 
                0 8px 25px -8px rgba(255, 138, 76, 0.2),
                0 0 0 1px rgba(255, 179, 71, 0.2);
            border-color: rgba(255, 179, 71, 0.3);
        }
        
        .nav-icon {
            margin-right: 1rem;
            width: 24px;
            height: 24px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .nav-item:hover .nav-icon {
            color: #ff8a4c;
            transform: scale(1.1);
        }
        
        .nav-text {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            white-space: nowrap;
        }
        
        /* Logo and Toggle */
        .logo-container {
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(255, 179, 71, 0.08) 0%, transparent 100%);
            position: relative;
            z-index: 1;
        }
        
        .toggle-btn {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            backdrop-filter: blur(10px);
        }
        
        .toggle-btn:hover {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.2) 0%, rgba(255, 179, 71, 0.15) 100%);
            color: #ff8a4c;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(255, 138, 76, 0.3);
        }
        
        .expand-icon {
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        /* User Menu */
        .user-menu {
            margin-top: auto;
            padding: 1.5rem 2rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.1) 100%);
            position: relative;
            z-index: 1;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .user-info:hover {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.15) 0%, rgba(255, 179, 71, 0.1) 100%);
            border-color: rgba(255, 179, 71, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(255, 138, 76, 0.3);
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #ff8a4c 0%, #ffb347 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #ffffff;
            margin-right: 1rem;
            flex-shrink: 0;
            font-size: 0.875rem;
            box-shadow: 
                0 4px 12px rgba(255, 138, 76, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .user-details {
            flex-grow: 1;
            min-width: 0;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9375rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .user-email {
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 0.25rem;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            bottom: 100%;
            left: 1.5rem;
            right: 1.5rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, rgba(26, 35, 50, 0.95) 0%, rgba(15, 20, 25, 0.95) 100%);
            border-radius: 12px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            z-index: 50;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .dropdown-menu:not(.hidden) {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }
        
        .dropdown-item {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            font-size: 0.9375rem;
            font-weight: 500;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.15) 0%, rgba(255, 179, 71, 0.1) 100%);
            color: #ffb347;
            transform: translateX(8px);
        }
        
        .dropdown-icon {
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dropdown-item:hover .dropdown-icon {
            color: #ff8a4c;
            transform: scale(1.1);
        }
        
        /* Mobile Styles */
        .mobile-header {
            display: none;
            padding: 1.5rem;
            background: linear-gradient(135deg, #1a2332 0%, #0f1419 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 40;
        }
        
        .mobile-logo {
            height: 32px;
            color: #ffffff;
            filter: brightness(1.2);
        }
        
        .mobile-menu-btn {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .mobile-menu-btn:hover {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.2) 0%, rgba(255, 179, 71, 0.15) 100%);
            color: #ff8a4c;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(255, 138, 76, 0.3);
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -300px;
                top: 0;
                bottom: 0;
                z-index: 50;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .mobile-header {
                display: flex;
            }
        }
        
        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem;
            min-height: 100vh;
        }
        
        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.5) 0%, rgba(255, 179, 71, 0.3) 100%);
            border-radius: 2px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, rgba(255, 138, 76, 0.7) 0%, rgba(255, 179, 71, 0.5) 100%);
        }
        
        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 45;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        
        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Enhanced App Logo Styling */
        .sidebar .logo-container a {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        
        /* Ripple Effect */
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 179, 71, 0.3) 0%, transparent 70%);
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen bg-white">
@php
    $firstProjectId = \App\Models\MasterProject::first()?->id ?? 1;
@endphp

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<div class="flex min-h-screen">
    <!-- Desktop Sidebar -->
    <div class="sidebar flex flex-col">
        <div class="logo-container">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
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
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Dashboard') }}</span>
            </a>

            <!-- Project Group -->
            <div class="group-heading">{{ __('Project') }}</div>
            
            <a href="{{ route('master-projects.index') }}" class="nav-item {{ request()->routeIs('master-projects.index') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Master Project') }}</span>
            </a>
            
            <a href="{{ route('rab.index') }}" class="nav-item {{ request()->routeIs('rab.*') ? 'active-nav' : '' }}">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('RAB Proyek') }}</span>
            </a>
            
            <a href="{{ route('wbs.index') }}" class="nav-item {{ request()->routeIs('wbs.index') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('WBS') }}</span>
            </a>

            <!-- Finance Group -->
            <div class="group-heading">{{ __('Finance') }}</div>
            
            <a href="{{ route('account.index') }}" class="nav-item {{ request()->routeIs('account.*') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Account') }}</span>
            </a>
            
            <a href="{{ route('vendor.index') }}" class="nav-item {{ request()->routeIs('vendor.*') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Vendor') }}</span>
            </a>
            
            <a href="{{ route('purchase.index') }}" class="nav-item {{ request()->routeIs('purchase.*') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Purchase') }}</span>
            </a>
            
            <a href="{{ route('generalleadger.index') }}" class="nav-item {{ request()->routeIs('generalleadger.index') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('General Ledger') }}</span>
            </a>
            
            <a href="{{ route('incomestatement.index') }}" class="nav-item {{ request()->routeIs('incomestatement.index') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Income Statement') }}</span>
            </a>

            <a href="{{ route('rab-weekly.index') }}" class="nav-item {{ request()->routeIs('rab-weekly.*') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('RAB Perminggu') }}</span>
            </a>
            
            <a href="{{ route('eva.index') }}" class="nav-item {{ request()->routeIs('eva.*') ? 'active-nav' : '' }}" wire:navigate>
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('EVA') }}</span>
            </a>
            
            <!-- Documentation -->
            <div class="group-heading">{{ __('Documentation') }}</div>
            
            <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="nav-item">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Repository') }}</span>
            </a>
            
            <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="nav-item">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <span class="nav-text">{{ __('Documentation') }}</span>
            </a>
        </div>

        <!-- User Menu -->
        <div class="user-menu">
            <div class="relative">
                <div class="user-info" id="userMenuToggle">
                    <div class="user-avatar">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-email">{{ auth()->user()->email }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="color: rgba(255, 255, 255, 0.5); transition: all 0.3s ease; margin-left: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                
                <div class="dropdown-menu hidden" id="userDropdownMenu">
                    <a href="{{ route('settings.profile') }}" class="dropdown-item" wire:navigate>
                        <div class="dropdown-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        {{ __('Settings') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="dropdown-item w-full text-left">
                            <div class="dropdown-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
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
        <a href="{{ route('dashboard') }}" wire:navigate>
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
    // Enhanced sidebar functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const userMenuToggle = document.getElementById('userMenuToggle');
        const userDropdown = document.getElementById('userDropdownMenu');
        const mobileMenuBtn = document.getElementById('mobileMenuToggle');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        // Toggle sidebar collapse
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
            });
        }

        // Toggle user dropdown
        if (userMenuToggle && userDropdown) {
            userMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (userDropdown && !userMenuToggle.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Mobile menu toggle
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            });
        }
        
        // Close mobile menu when clicking overlay
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
            });
        }
        
        // Add ripple effect to navigation items
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Create ripple element
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.className = 'ripple';
                ripple.style.cssText = `
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                `;
                
                // Ensure parent has relative positioning
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                // Remove ripple after animation
                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.parentNode.removeChild(ripple);
                    }
                }, 600);
            });
        });
        
        // Enhanced hover effects for user menu
        if (userMenuToggle) {
            const chevron = userMenuToggle.querySelector('svg:last-child');
            userMenuToggle.addEventListener('mouseenter', function() {
                if (chevron) {
                    chevron.style.color = '#ff8a4c';
                    chevron.style.transform = 'rotate(180deg)';
                }
            });
            
            userMenuToggle.addEventListener('mouseleave', function() {
                if (chevron && userDropdown.classList.contains('hidden')) {
                    chevron.style.color = 'rgba(255, 255, 255, 0.5)';
                    chevron.style.transform = 'rotate(0deg)';
                }
            });
        }
        
        // Smooth scrolling for navigation
        const scrollContainer = document.querySelector('.sidebar .flex-grow');
        if (scrollContainer) {
            scrollContainer.style.scrollBehavior = 'smooth';
        }
    });
</script>

@fluxScripts
</body>
</html>