@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Email analytics</h2>
            <p class="mt-1 text-sm text-slate-500">
                Overview of sent, failed and queued messages across your campaigns.
            </p>
        </div>
    </div>

    {{-- Two-column layout: left = Summary, right = Campaigns --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Card 1: KPI summary (left) --}}
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/60 rounded-t-2xl">
                <h3 class="text-base font-semibold text-slate-900">Summary</h3>
                <p class="mt-1 text-xs text-slate-500">
                    High‑level delivery stats for all campaigns.
                </p>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 gap-4">
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                        <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Sent</p>
                        <p class="mt-1 text-2xl font-semibold text-emerald-900">{{ number_format($totalSent) }}</p>
                        <p class="mt-1 text-xs text-emerald-800/80">Successfully delivered emails.</p>
                    </div>
                    <div class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3">
                        <p class="text-xs font-medium text-rose-700 uppercase tracking-wide">Failed</p>
                        <p class="mt-1 text-2xl font-semibold text-rose-700">{{ number_format($totalFailed) }}</p>
                        <p class="mt-1 text-xs text-rose-800/80">Bounced or errored messages.</p>
                    </div>
                    <div class="rounded-xl border border-sky-100 bg-sky-50 px-4 py-3">
                        <p class="text-xs font-medium text-sky-700 uppercase tracking-wide">Queued</p>
                        <p class="mt-1 text-2xl font-semibold text-sky-900">{{ number_format($totalQueued) }}</p>
                        <p class="mt-1 text-xs text-sky-800/80">Waiting to be processed.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Campaign table (right) --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/60 rounded-t-2xl flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900">Campaigns</h3>
                @if($byCampaign->count())
                    <span class="text-xs text-slate-500">{{ $byCampaign->count() }} recent</span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">Campaign</th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">Sent</th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">Failed</th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($byCampaign as $campaign)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-3 align-middle">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-900">{{ $campaign->name }}</span>
                                        <span class="text-[11px] text-slate-500">
                                            {{ $campaign->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle">
                                    @if($campaign->status === 'draft')
                                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-[11px] font-medium text-yellow-800">
                                            Draft
                                        </span>
                                    @elseif($campaign->status === 'queued')
                                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-[11px] font-medium text-sky-800">
                                            Queued
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-medium text-emerald-800">
                                            Sent
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 align-middle text-emerald-700 font-medium">
                                    {{ $campaign->sent_count }}
                                </td>
                                <td class="px-6 py-3 align-middle text-rose-600 font-medium">
                                    {{ $campaign->failed_count }}
                                </td>
                                <td class="px-6 py-3 align-middle text-slate-800">
                                    {{ $campaign->sent_count + $campaign->failed_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                    No campaigns yet. Once you start sending, delivery stats will appear here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
