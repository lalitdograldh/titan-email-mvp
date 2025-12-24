@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($stats as $key => $value)
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 uppercase tracking-wide">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                    <p class="mt-1 text-3xl font-semibold text-slate-900">{{ number_format($value) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <span class="text-white text-xl font-bold">●</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('leads.index') }}" class="btn-primary text-center py-4">Manage Leads</a>
            <a href="{{ route('campaigns.create') }}" class="btn-primary text-center py-4">New Campaign</a>
            <a href="{{ route('outlook-accounts.index') }}" class="btn-secondary text-center py-4">Outlook Accounts</a>
            <a href="{{ route('leads.index') }}#scrape" class="btn-secondary text-center py-4">Scrape Leads</a>
        </div>
    </div>
    
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Recent Activity</h3>
        <div class="space-y-3">
            <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <span class="text-sm text-slate-700">Campaign "Welcome Series" sent to 127 leads</span>
            </div>
            <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                <span class="text-sm text-slate-700">Added Outlook account: team@company.com</span>
            </div>
        </div>
    </div>
</div>
@endsection
