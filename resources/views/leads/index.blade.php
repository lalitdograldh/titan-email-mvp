@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Scrape Card --}}
    <div class="card p-6" id="scrape">
        <h3 class="text-xl font-semibold text-slate-900 mb-4">Scrape website for leads</h3>
        <form method="POST" action="{{ route('leads.scrape') }}" class="space-y-4">
            @csrf
            <div>
                <input type="url" name="url" placeholder="https://example.com" required
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-2 text-xs text-slate-500">Only public pages are scanned. Results are stored as new leads if emails are found.</p>
            </div>
            <button type="submit" class="btn-primary">Scrape Website</button>
        </form>
    </div>

    {{-- Add Lead Card --}}
    <div class="card p-6" id="add-lead">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-slate-900">Add lead</h3>
            <span class="text-xs text-slate-500">Create a lead manually.</span>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('leads.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Lalit Dogra"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
                <input type="text" name="company" value="{{ old('company') }}" placeholder="Titan Group"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="founder@example.com"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website') }}" placeholder="https://company.com"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="md:col-span-2 flex items-center justify-between pt-2">
                <p class="text-xs text-slate-500">
                    Only basic contact details are required for this MVP. <span class="text-red-500 font-medium">*</span> = Required
                </p>
                <button type="submit" class="btn-primary">
                    Save lead
                </button>
            </div>
        </form>
    </div>

    {{-- Leads Table --}}
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-slate-900">Leads ({{ $leads->total() }})</h3>
                <a href="#add-lead" class="btn-primary">Add Lead</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Website</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($leads as $lead)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $lead->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $lead->company ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="mailto:{{ $lead->email }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ $lead->email }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                @if($lead->phone)
                                    <a href="tel:{{ $lead->phone }}" class="text-indigo-600 hover:text-indigo-500 font-medium">{{ $lead->phone }}</a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lead->website)
                                    <a href="{{ $lead->website }}" target="_blank" rel="noopener noreferrer"
                                       class="text-sm text-indigo-600 hover:text-indigo-500 truncate max-w-xs block">
                                        {{ $lead->website }}
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex items-center gap-3">
                                <a href="{{ route('leads.edit', $lead) }}"
                                   class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('leads.destroy', $lead) }}"
                                      onsubmit="return confirm('Delete this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                No leads yet. Use the scraper above or add manually.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $leads->links() }}
        </div>
    </div>
</div>
@endsection
