<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Titan Email MVP') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white border-r border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <h1 class="text-2xl font-bold text-slate-900">Titan Email</h1>
                <p class="text-sm text-slate-500 mt-1">Email Marketing MVP</p>
            </div>
            <nav class="mt-8">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-500' : '' }}">
                    <span class="w-5 h-5 bg-indigo-500 rounded-sm mr-3"></span>
                    Dashboard
                </a>
                <a href="{{ route('leads.index') }}" class="flex items-center px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium {{ request()->routeIs('leads.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-500' : '' }}">
                    <span class="w-5 h-5 bg-green-500 rounded-sm mr-3"></span>
                    Leads
                </a>
                <a href="{{ route('campaigns.index') }}" class="flex items-center px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium {{ request()->routeIs('campaigns.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-500' : '' }}">
                    <span class="w-5 h-5 bg-blue-500 rounded-sm mr-3"></span>
                    Campaigns
                </a>
                <a href="{{ route('outlook-accounts.index') }}" class="flex items-center px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium {{ request()->routeIs('outlook-accounts.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-500' : '' }}">
                    <span class="w-5 h-5 bg-orange-500 rounded-sm mr-3"></span>
                    Outlook Accounts
                </a>
                <a href="{{ route('analytics.index') }}" class="flex items-center px-6 py-3 text-slate-700 hover:bg-slate-50 font-medium {{ request()->routeIs('analytics.*') ? 'bg-indigo-50 text-indigo-700 border-r-2 border-indigo-500' : '' }}">
                    <span class="w-5 h-5 bg-purple-500 rounded-sm mr-3"></span>
                    Analytics
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b border-slate-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-slate-900">{{ $header ?? 'Dashboard' }}</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-slate-500">Demo MVP - Mock Sending</span>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
