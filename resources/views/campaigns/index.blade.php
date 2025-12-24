@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-semibold text-slate-900">Campaigns</h3>
        <a href="{{ route('campaigns.create') }}" class="btn-primary">New Campaign</a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="card p-4 bg-green-50 border-green-200">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="card p-4 bg-yellow-50 border-yellow-200">
            <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
        </div>
    @endif

    {{-- Campaigns table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Emails Sent
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($campaigns as $campaign)
                        <tr>
                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">
                                    {{ $campaign->name }}
                                </div>
                            </td>

                            {{-- Subject --}}
                            <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-xs">
                                {{ $campaign->subject }}
                            </td>

                            {{-- Status badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($campaign->status === 'draft')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                        Draft
                                    </span>
                                @elseif($campaign->status === 'queued')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        Queued
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        Sent
                                    </span>
                                @endif
                            </td>

                            {{-- Emails Sent (uses withCount results) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $campaign->sent_email_logs_count }} / {{ $campaign->email_logs_count }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex items-center gap-3">
                                @if($campaign->status === 'draft')
                                    <a href="{{ route('campaigns.edit', $campaign) }}"
                                       class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </a>
                                @endif

                                @if($campaign->status !== 'sent')
                                    <form method="POST"
                                          action="{{ route('campaigns.send.form', $campaign) }}"
                                          class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100"
                                                onclick="return confirm('Send to {{ $campaign->email_logs_count }} leads?')">
                                            Send
                                        </button>
                                    </form>
                                @endif
                                {{-- Delete (always allowed, or restrict as you want) --}}
                                <form method="POST"
                                    action="{{ route('campaigns.destroy', $campaign) }}"
                                    class="inline"
                                    onsubmit="return confirm('Delete this campaign and its email logs?');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                No campaigns yet.
                                <a href="{{ route('campaigns.create') }}"
                                   class="text-indigo-600 hover:text-indigo-500 font-medium">
                                    Create one
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $campaigns->links() }}
        </div>
    </div>
</div>
@endsection
